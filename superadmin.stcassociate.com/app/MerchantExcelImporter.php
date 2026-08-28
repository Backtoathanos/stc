<?php

namespace App;

use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;

class MerchantExcelImporter
{
    /** @var array<string,int> */
    private $cityIds = [];

    /** @var array<string,int> */
    private $stateIds = [];

    public static function templateHeaders(): array
    {
        return [
            'Vendor Code',
            'Vendor Name',
            'Address',
            'City',
            'State',
            'Contact Person',
            'Contact No.',
            'Fax No.',
            'E.Mail',
            'PAN',
            'GSTIN',
            'Active',
        ];
    }

    public function writeTemplate(string $path): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Merchants');
        $headers = self::templateHeaders();
        foreach ($headers as $i => $header) {
            $sheet->setCellValueByColumnAndRow($i + 1, 1, $header);
        }
        $sheet->getStyle('A1:L1')->getFont()->setBold(true)->getColor()->setRGB('FF0000');
        $sheet->getStyle('A1:L1')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('FFFF00');
        $sheet->getStyle('A1:L1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        foreach (range('A', 'L') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $writer = new Xlsx($spreadsheet);
        $writer->save($path);
        $spreadsheet->disconnectWorksheets();
    }

    /**
     * @return array{inserted:int,updated:int,skipped:int,errors:array<int,string>}
     */
    public function import(UploadedFile $file): array
    {
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $this->loadLookups();

        $headerRow = $this->findHeaderRow($sheet);
        if ($headerRow === null) {
            $spreadsheet->disconnectWorksheets();
            return [
                'inserted' => 0,
                'updated' => 0,
                'skipped' => 0,
                'errors' => ['Could not find a header row. Vendor Name or GSTIN is required to match merchants.'],
            ];
        }

        $colMap = $this->mapHeaderColumns($sheet, $headerRow);
        if (!isset($colMap['name']) && !isset($colMap['gstin'])) {
            $spreadsheet->disconnectWorksheets();
            return [
                'inserted' => 0,
                'updated' => 0,
                'skipped' => 0,
                'errors' => ['Header "Vendor Name" or "GSTIN" is required to match records.'],
            ];
        }

        $highestRow = (int) $sheet->getHighestDataRow();
        $inserted = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];
        $seenNames = [];
        $seenGstins = [];
        $foundBy = (int) (Auth::id() ?: 0);

        for ($row = $headerRow + 1; $row <= $highestRow; $row++) {
            $name = isset($colMap['name']) ? $this->blankable($this->cellAt($sheet, $colMap['name'], $row)) : '';
            $excel = $this->excelValues($sheet, $colMap, $row, $name);
            $gstinKey = $this->normalizeGstin($excel['gstin']);

            if ($excel['name'] === '' && $gstinKey === '') {
                $skipped++;
                continue;
            }

            $nameKey = $excel['name'] !== '' ? mb_strtoupper($excel['name'], 'UTF-8') : '';
            if ($nameKey !== '' && isset($seenNames[$nameKey])) {
                $skipped++;
                continue;
            }
            if ($gstinKey !== '' && isset($seenGstins[$gstinKey])) {
                $skipped++;
                continue;
            }
            if ($nameKey !== '') {
                $seenNames[$nameKey] = true;
            }
            if ($gstinKey !== '') {
                $seenGstins[$gstinKey] = true;
            }

            try {
                $existing = $this->findExisting($excel);
                if ($existing) {
                    $fill = $this->emptyFieldsFromExcel($existing, $excel);
                    if ($fill === []) {
                        $skipped++;
                        continue;
                    }
                    Merchant::where('stc_merchant_id', $existing->stc_merchant_id)->update($fill);
                    $updated++;
                } else {
                    if ($excel['name'] === '') {
                        $skipped++;
                        continue;
                    }
                    Merchant::create($this->createPayload($excel, $foundBy));
                    $inserted++;
                }
            } catch (\Throwable $e) {
                $label = $excel['name'] !== '' ? $excel['name'] : $excel['gstin'];
                $errors[] = 'Row ' . $row . ' (' . $label . '): ' . $e->getMessage();
                $skipped++;
            }
        }

        $spreadsheet->disconnectWorksheets();

        return [
            'inserted' => $inserted,
            'updated' => $updated,
            'skipped' => $skipped,
            'errors' => $errors,
        ];
    }

    private function loadLookups(): void
    {
        $this->cityIds = [];
        foreach (City::query()->get(['stc_city_id', 'stc_city_name']) as $city) {
            $key = $this->lookupKey($city->stc_city_name);
            if ($key !== '') {
                $this->cityIds[$key] = (int) $city->stc_city_id;
            }
        }
        $this->stateIds = [];
        foreach (State::query()->get(['stc_state_id', 'stc_state_name']) as $state) {
            $key = $this->lookupKey($state->stc_state_name);
            if ($key !== '') {
                $this->stateIds[$key] = (int) $state->stc_state_id;
            }
        }
    }

    private function lookupKey($value): string
    {
        return mb_strtoupper(trim((string) $value), 'UTF-8');
    }

    private function findHeaderRow(Worksheet $sheet): ?int
    {
        $highestRow = min(15, (int) $sheet->getHighestDataRow());
        for ($row = 1; $row <= $highestRow; $row++) {
            $map = $this->mapHeaderColumns($sheet, $row);
            if (isset($map['name']) || isset($map['gstin'])) {
                return $row;
            }
        }

        return null;
    }

    /**
     * @return array<string,int>
     */
    private function mapHeaderColumns(Worksheet $sheet, int $row): array
    {
        $highestCol = $sheet->getHighestDataColumn($row);
        $map = [];
        $colCount = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestCol);
        for ($col = 1; $col <= $colCount; $col++) {
            $key = $this->headerAlias($this->cellAt($sheet, $col, $row));
            if ($key !== '' && !isset($map[$key])) {
                $map[$key] = $col;
            }
        }

        return $map;
    }

    private function headerAlias(string $header): string
    {
        $n = strtolower(trim($header));
        $n = str_replace(['.', '-', '_'], ' ', $n);
        $n = preg_replace('/\s+/', ' ', $n);
        $n = str_replace(' ', '', (string) $n);
        $aliases = [
            'vendorname' => 'name',
            'merchantname' => 'name',
            'productname' => 'name',
            'name' => 'name',
            'address' => 'address',
            'city' => 'city',
            'state' => 'state',
            'contactperson' => 'contact_person',
            'contactno' => 'phone',
            'contactnumber' => 'phone',
            'phone' => 'phone',
            'mobileno' => 'phone',
            'mobile' => 'phone',
            'email' => 'email',
            'mail' => 'email',
            'pan' => 'pan',
            'gstin' => 'gstin',
            'gst' => 'gstin',
            'gstno' => 'gstin',
        ];

        return $aliases[$n] ?? '';
    }

    private function cellAt(Worksheet $sheet, int $col, int $row): string
    {
        return $this->cellToString($sheet->getCellByColumnAndRow($col, $row));
    }

    private function cellToString(Cell $cell): string
    {
        $v = $cell->getValue();
        if ($v instanceof RichText) {
            $v = $v->getPlainText();
        }
        if ($v === null) {
            return '';
        }
        if (is_bool($v)) {
            return $v ? 'Yes' : 'No';
        }
        if (is_int($v)) {
            return (string) $v;
        }
        if (is_float($v)) {
            if (floor($v) == $v) {
                return sprintf('%.0f', $v);
            }

            return rtrim(rtrim(sprintf('%.12F', $v), '0'), '.');
        }

        return trim((string) $v);
    }

    private function blankable(string $value): string
    {
        $v = trim($value);
        if ($v === '') {
            return '';
        }
        $cmp = strtoupper($v);
        if (in_array($cmp, ['NA', 'N/A', 'N.A.', 'N.A', 'NIL', 'NULL', '-', '--', '.'], true)) {
            return '';
        }

        return $v;
    }

    /**
     * @param array<string,int> $colMap
     * @return array<string,string>
     */
    private function excelValues(Worksheet $sheet, array $colMap, int $row, string $name): array
    {
        $get = function ($key) use ($sheet, $colMap, $row) {
            if (!isset($colMap[$key])) {
                return '';
            }

            return $this->blankable($this->cellAt($sheet, $colMap[$key], $row));
        };

        return [
            'name' => $name,
            'address' => $get('address'),
            'city' => $get('city'),
            'state' => $get('state'),
            'contact_person' => $get('contact_person'),
            'phone' => $get('phone'),
            'email' => $get('email'),
            'pan' => strtoupper($get('pan')),
            'gstin' => strtoupper($get('gstin')),
        ];
    }

    /**
     * Fill only merchant fields that are currently empty.
     *
     * @param array<string,string> $excel
     * @return array<string,mixed>
     */
    private function emptyFieldsFromExcel(Merchant $existing, array $excel): array
    {
        $fill = [];
        $textMap = [
            'stc_merchant_name' => $excel['name'],
            'stc_merchant_address' => $excel['address'],
            'stc_merchant_contact_person' => $excel['contact_person'],
            'stc_merchant_phone' => $excel['phone'],
            'stc_merchant_email' => $excel['email'],
            'stc_merchant_pan' => $excel['pan'],
            'stc_merchant_gstin' => $excel['gstin'],
        ];
        foreach ($textMap as $column => $value) {
            if ($value === '') {
                continue;
            }
            if ($this->isBlankDb($existing->{$column})) {
                $fill[$column] = $value;
            }
        }

        if ($excel['city'] !== '' && (int) $existing->stc_merchant_city_id === 0) {
            $fill['stc_merchant_city_id'] = $this->resolveCityId($excel['city']);
        }
        if ($excel['state'] !== '' && (int) $existing->stc_merchant_state_id === 0) {
            $fill['stc_merchant_state_id'] = $this->resolveStateId($excel['state']);
        }

        return $fill;
    }

    private function isBlankDb($value): bool
    {
        $v = trim((string) ($value ?? ''));
        if ($v === '') {
            return true;
        }

        return in_array(strtoupper($v), ['NA', 'N/A', 'N.A.', 'N.A', 'NIL', 'NULL', '-', '--'], true);
    }

    /**
     * @param array<string,string> $excel
     */
    private function createPayload(array $excel, int $foundBy): array
    {
        return [
            'stc_merchant_name' => $excel['name'],
            'stc_merchant_address' => $excel['address'],
            'stc_merchant_city_id' => $excel['city'] !== '' ? $this->resolveCityId($excel['city']) : 0,
            'stc_merchant_state_id' => $excel['state'] !== '' ? $this->resolveStateId($excel['state']) : 0,
            'stc_merchant_contact_person' => $excel['contact_person'],
            'stc_merchant_email' => $excel['email'],
            'stc_merchant_phone' => $excel['phone'],
            'stc_merchant_pan' => $excel['pan'],
            'stc_merchant_gstin' => $excel['gstin'],
            'stc_merchant_specially_known_for' => '',
            'stc_merchant_category' => '',
            'stc_merchant_image' => null,
            'stc_merchant_found_by' => $foundBy,
        ];
    }

    private function resolveCityId(string $name): int
    {
        $key = $this->lookupKey($name);
        if (isset($this->cityIds[$key])) {
            return $this->cityIds[$key];
        }
        $city = City::create([
            'stc_city_name' => trim(ucwords(strtolower($name))),
            'stc_city_status' => 1,
        ]);
        $id = (int) $city->stc_city_id;
        $this->cityIds[$key] = $id;

        return $id;
    }

    private function resolveStateId(string $name): int
    {
        $key = $this->lookupKey($name);
        if (isset($this->stateIds[$key])) {
            return $this->stateIds[$key];
        }
        $state = State::create([
            'stc_state_name' => trim(ucwords(strtolower($name))),
            'stc_state_status' => 1,
        ]);
        $id = (int) $state->stc_state_id;
        $this->stateIds[$key] = $id;

        return $id;
    }

    /**
     * @param array<string,string> $excel
     */
    private function findExisting(array $excel): ?Merchant
    {
        if ($excel['name'] !== '') {
            $byName = $this->findByName($excel['name']);
            if ($byName) {
                return $byName;
            }
        }
        if ($excel['gstin'] !== '') {
            return $this->findByGstin($excel['gstin']);
        }

        return null;
    }

    private function findByName(string $name): ?Merchant
    {
        return Merchant::query()
            ->whereRaw('UPPER(TRIM(stc_merchant_name)) = ?', [mb_strtoupper($name, 'UTF-8')])
            ->first();
    }

    private function normalizeGstin(string $gstin): string
    {
        return strtoupper((string) preg_replace('/[\s\-]/', '', trim($gstin)));
    }

    private function findByGstin(string $gstin): ?Merchant
    {
        $norm = $this->normalizeGstin($gstin);
        if ($norm === '') {
            return null;
        }

        return Merchant::query()
            ->whereRaw(
                "UPPER(REPLACE(REPLACE(TRIM(IFNULL(stc_merchant_gstin, '')), ' ', ''), '-', '')) = ?",
                [$norm]
            )
            ->first();
    }
}
