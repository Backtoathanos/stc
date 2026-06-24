<?php

namespace App\Console\Commands;

use App\SchoolStudent;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class ImportSchoolStudents extends Command
{
    /**
     * php artisan school:import-students
     * php artisan school:import-students --file="/path/to/file.xlsx"
     * php artisan school:import-students --dry-run
     */
    protected $signature = 'school:import-students
                            {--file=  : Absolute path to the XLSX file (defaults to SGMS STD.xlsx in the app root)}
                            {--dry-run : Preview changes without writing to the database}';

    protected $description = 'Sync students from SGMS STD.xlsx into stc_school_student (insert new, update existing)';

    // Excel column letters → description
    private const COL_SL        = 'A';
    private const COL_DOA       = 'B';
    private const COL_NAME      = 'C';
    private const COL_CLASS     = 'D';
    private const COL_FATHER    = 'E';
    private const COL_MOTHER    = 'F';
    private const COL_ADDRESS   = 'G';
    private const COL_SARA_ID   = 'H';  // unique student ID
    private const COL_DOB       = 'I';
    private const COL_AADHAR    = 'J';
    private const COL_STU_CODE  = 'K';
    private const COL_GENDER    = 'L';
    private const COL_RELIGION  = 'M';
    private const COL_BOARDING  = 'N';
    private const COL_CONTACT   = 'O';

    /** @var array<string,string> */
    private array $classCache = [];

    public function handle(): int
    {
        $filePath = trim((string) ($this->option('file') ?: base_path('SGMS STD.xlsx')));
        $dryRun   = (bool) $this->option('dry-run');

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        $this->info("Loading file: {$filePath}");

        if ($dryRun) {
            $this->warn('DRY-RUN mode — no changes will be written to the database.');
        }

        $spreadsheet = IOFactory::load($filePath);
        $ws          = $spreadsheet->getActiveSheet();
        $totalRows   = $ws->getHighestRow();

        // Data starts at row 5 (rows 1-4 are school info + column headers)
        $dataStart = 5;

        $inserted = 0;
        $updated  = 0;
        $skipped  = 0;
        $failures = [];

        $bar = $this->output->createProgressBar($totalRows - $dataStart + 1);
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% — %message%');
        $bar->setMessage('Starting…');
        $bar->start();

        for ($row = $dataStart; $row <= $totalRows; $row++) {
            $bar->setMessage("Row {$row}");
            $bar->advance();

            $saraId = trim((string) $ws->getCell(self::COL_SARA_ID . $row)->getValue());
            $slNo   = trim((string) $ws->getCell(self::COL_SL . $row)->getValue());

            // Skip class-group banner rows (e.g. "CLASS-V") and blank rows
            if ($saraId === '' || !is_numeric($slNo)) {
                $skipped++;
                continue;
            }

            try {
                $record = $this->buildRecord($ws, $row, $saraId);
            } catch (\Throwable $e) {
                $failures[] = "Row {$row} ({$saraId}): " . $e->getMessage();
                $skipped++;
                continue;
            }

            $existing = SchoolStudent::where('stc_school_student_studid', $saraId)->first();

            if ($existing) {
                $updatePayload = $record;
                unset($updatePayload['stc_school_student_studid'],
                      $updatePayload['stc_school_student_createdate'],
                      $updatePayload['stc_school_student_createdby']);

                if (!$dryRun) {
                    $existing->fill($updatePayload)->save();
                }
                $updated++;
            } else {
                if (!$dryRun) {
                    SchoolStudent::create($record);
                }
                $inserted++;
            }
        }

        $bar->setMessage('Done');
        $bar->finish();
        $this->line('');
        $this->line('');

        $this->table(
            ['Result', 'Count'],
            [
                ['Inserted (new students)', $inserted],
                ['Updated  (existing students)', $updated],
                ['Skipped  (headers / blank rows)', $skipped],
            ]
        );

        if ($failures) {
            $this->line('');
            $this->warn('The following rows could not be processed:');
            foreach ($failures as $msg) {
                $this->line("  - {$msg}");
            }
        }

        $this->line('');
        $this->info($dryRun ? 'Dry-run complete — nothing was saved.' : 'Import complete.');

        return 0;
    }

    /**
     * Build the data array for one student row.
     *
     * @return array<string,mixed>
     */
    private function buildRecord($ws, int $row, string $saraId): array
    {
        // Name: first word = firstname, remaining = lastname
        $fullName  = trim((string) $ws->getCell(self::COL_NAME . $row)->getValue());
        $space     = strpos($fullName, ' ');
        $firstName = $space !== false ? substr($fullName, 0, $space) : $fullName;
        $lastName  = $space !== false ? trim(substr($fullName, $space + 1)) : '';

        $classTitle = trim((string) $ws->getCell(self::COL_CLASS . $row)->getValue());
        $classId    = $this->resolveClassId($classTitle);

        $dob = $this->parseExcelDate($ws->getCell(self::COL_DOB . $row)->getValue());
        $doa = $this->parseExcelDate($ws->getCell(self::COL_DOA . $row)->getValue());

        $gender   = strtoupper(trim((string) $ws->getCell(self::COL_GENDER . $row)->getValue()));
        $religion = trim((string) $ws->getCell(self::COL_RELIGION . $row)->getValue());
        $address  = trim((string) $ws->getCell(self::COL_ADDRESS . $row)->getValue());
        $father   = trim((string) $ws->getCell(self::COL_FATHER . $row)->getValue());
        $mother   = trim((string) $ws->getCell(self::COL_MOTHER . $row)->getValue());
        // Take only the first contact number; cells may use " / ", " ", or "," as separators
        $contactRaw   = trim((string) $ws->getCell(self::COL_CONTACT . $row)->getValue());
        $contactParts = preg_split('/[\s\/,]+/', $contactRaw, -1, PREG_SPLIT_NO_EMPTY);
        $contact      = trim($contactParts[0] ?? '');
        $aadhar   = trim((string) $ws->getCell(self::COL_AADHAR . $row)->getValue());
        $stuCode  = trim((string) $ws->getCell(self::COL_STU_CODE . $row)->getValue());
        $boarding = trim((string) $ws->getCell(self::COL_BOARDING . $row)->getValue());

        // Pack extra fields that have no dedicated column into remarks
        $remarkParts = array_filter([
            'artisan-import',
            $aadhar   !== '' ? "Aadhar: {$aadhar}"    : null,
            $stuCode  !== '' ? "Code: {$stuCode}"      : null,
            $mother   !== '' ? "Mother: {$mother}"     : null,
            $boarding !== '' ? "Boarding: {$boarding}" : null,
        ]);
        $remarks = implode(' | ', $remarkParts);

        return [
            'stc_school_student_studid'        => $saraId,
            'stc_school_student_firstname'     => $firstName,
            'stc_school_student_lastname'      => $lastName,
            'stc_school_student_dob'           => $dob,
            'stc_school_student_gender'        => $gender,
            'stc_school_student_bloodgroup'    => '',
            'stc_school_student_email'         => '',
            'stc_school_student_contact'       => $contact,
            'stc_school_student_address'       => $address,
            'stc_school_student_religion'      => $religion,
            'stc_school_student_admissiondate' => $doa,
            'stc_school_student_classroomid'   => $classId,
            'stc_school_student_guardianname'  => $father,
            'stc_school_student_remarks'       => $remarks,
            'stc_school_student_status'        => '1',
            'stc_school_student_createdate'    => now()->format('Y-m-d H:i:s'),
            'stc_school_student_createdby'     => 0,
        ];
    }

    /**
     * Resolve a class title (e.g. "V", "VI") to its stc_school_class_id.
     * Returns '0' when no matching class row is found.
     */
    private function resolveClassId(string $classTitle): string
    {
        $key = strtoupper(trim($classTitle));

        if ($key === '') {
            return '0';
        }

        if (isset($this->classCache[$key])) {
            return $this->classCache[$key];
        }

        $id = DB::table('stc_school_class')
            ->whereRaw('TRIM(UPPER(`stc_school_class_title`)) = ?', [$key])
            ->orWhereRaw('TRIM(UPPER(`stc_school_class_classid`)) = ?', [$key])
            ->value('stc_school_class_id');

        $this->classCache[$key] = $id ? (string) $id : '0';

        return $this->classCache[$key];
    }

    /**
     * Convert a raw cell value (Excel serial number or date string) to Y-m-d.
     * Returns null when the value is empty or cannot be parsed.
     */
    private function parseExcelDate($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        // Numeric Excel serial date
        if (is_numeric($value)) {
            try {
                return ExcelDate::excelToDateTimeObject((float) $value)->format('Y-m-d');
            } catch (\Throwable $e) {
                // fall through to string parsing
            }
        }

        // Try common string date formats
        foreach (['n/j/Y', 'd-m-Y', 'd/m/Y', 'Y-m-d', 'm/d/Y', 'j/n/Y'] as $fmt) {
            $dt = DateTime::createFromFormat($fmt, trim((string) $value));
            if ($dt !== false) {
                return $dt->format('Y-m-d');
            }
        }

        return null;
    }
}
