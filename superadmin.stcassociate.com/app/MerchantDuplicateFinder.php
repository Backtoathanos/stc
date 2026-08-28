<?php

namespace App;

use Illuminate\Support\Facades\DB;

class MerchantDuplicateFinder
{
    /** @var array<int,string> */
    private $parent = [];

    /** @var array<int,array<string,bool>> */
    private $rootGstins = [];

    /**
     * Duplicate groups. GSTIN matches are always included.
     * Name matches use token search (e.g. new / sona / agency) plus similar_text %.
     *
     * @return array<int,array<string,mixed>>
     */
    public function groups(int $minPct = 50): array
    {
        $minPct = max(30, min(95, $minPct));
        $rows = Merchant::query()
            ->leftJoin('stc_city', 'stc_city.stc_city_id', '=', 'stc_merchant.stc_merchant_city_id')
            ->leftJoin('stc_state', 'stc_state.stc_state_id', '=', 'stc_merchant.stc_merchant_state_id')
            ->select(
                'stc_merchant.*',
                'stc_city.stc_city_name',
                'stc_state.stc_state_name'
            )
            ->orderBy('stc_merchant.stc_merchant_id')
            ->get();

        $this->parent = [];
        $this->rootGstins = [];
        $merchants = [];
        foreach ($rows as $row) {
            $id = (int) $row->stc_merchant_id;
            $name = trim((string) ($row->stc_merchant_name ?? ''));
            $tokens = $this->nameTokens($name);
            $gstinKey = $this->normalizeGstin((string) ($row->stc_merchant_gstin ?? ''));
            $merchants[$id] = [
                'id' => $id,
                'name' => $name,
                'address' => (string) ($row->stc_merchant_address ?? ''),
                'city_id' => (int) ($row->stc_merchant_city_id ?? 0),
                'state_id' => (int) ($row->stc_merchant_state_id ?? 0),
                'city' => trim((string) ($row->stc_city_name ?? '')),
                'state' => trim((string) ($row->stc_state_name ?? '')),
                'category' => trim((string) ($row->stc_merchant_category ?? '')),
                'contact' => (string) ($row->stc_merchant_contact_person ?? ''),
                'phone' => (string) ($row->stc_merchant_phone ?? ''),
                'email' => (string) ($row->stc_merchant_email ?? ''),
                'pan' => (string) ($row->stc_merchant_pan ?? ''),
                'gstin' => (string) ($row->stc_merchant_gstin ?? ''),
                'gstin_key' => $gstinKey,
                'known_for' => (string) ($row->stc_merchant_specially_known_for ?? ''),
                'image' => (string) ($row->stc_merchant_image ?? ''),
                'tokens' => $tokens,
                'strong' => $this->strongTokens($tokens),
                'norm' => implode(' ', $tokens),
                'filled' => $this->filledScore($row),
            ];
            $this->parent[$id] = $id;
            if ($gstinKey !== '') {
                $this->rootGstins[$id] = [$gstinKey => true];
            }
        }

        $adhocCounts = $this->adhocUsageCounts();
        $poCounts = $this->countsByMerchantId('stc_purchase_product', 'stc_purchase_product_merchant_id');
        $tradingCounts = $this->countsByMerchantId('stc_trading_purchase', 'stc_trading_purchase_purchaser_id');
        foreach ($merchants as $id => $m) {
            $key = mb_strtoupper(trim($m['name']), 'UTF-8');
            $merchants[$id]['adhoc_uses'] = ($key !== '' && isset($adhocCounts[$key])) ? (int) $adhocCounts[$key] : 0;
            $merchants[$id]['po_uses'] = isset($poCounts[$id]) ? (int) $poCounts[$id] : 0;
            $merchants[$id]['trading_uses'] = isset($tradingCounts[$id]) ? (int) $tradingCounts[$id] : 0;
        }

        $pairMeta = [];

        $byGstin = [];
        foreach ($merchants as $m) {
            if ($m['gstin_key'] === '') {
                continue;
            }
            $byGstin[$m['gstin_key']][] = $m['id'];
        }
        foreach ($byGstin as $gstinKey => $ids) {
            if (count($ids) < 2) {
                continue;
            }
            for ($i = 0; $i < count($ids); $i++) {
                for ($j = $i + 1; $j < count($ids); $j++) {
                    $this->union($ids[$i], $ids[$j]);
                    $pairMeta[$this->pairKey($ids[$i], $ids[$j])] = $this->gstinPairMeta(
                        $merchants[$ids[$i]],
                        $merchants[$ids[$j]],
                        $gstinKey
                    );
                }
            }
        }

        $byStrong = [];
        foreach ($merchants as $m) {
            foreach ($m['strong'] as $token) {
                $byStrong[$token][] = $m['id'];
            }
        }
        $seenPair = [];
        foreach ($byStrong as $ids) {
            $ids = array_values(array_unique($ids));
            if (count($ids) < 2 || count($ids) > 150) {
                continue;
            }
            for ($i = 0; $i < count($ids); $i++) {
                for ($j = $i + 1; $j < count($ids); $j++) {
                    $a = $ids[$i];
                    $b = $ids[$j];
                    $pk = $this->pairKey($a, $b);
                    if (isset($seenPair[$pk])) {
                        continue;
                    }
                    $seenPair[$pk] = true;
                    if ($this->hasConflictingGstin($merchants[$a], $merchants[$b])) {
                        continue;
                    }
                    $score = $this->nameScore($merchants[$a], $merchants[$b]);
                    if ($score === null || (int) $score['pct'] < $minPct) {
                        continue;
                    }
                    if (!$this->union($a, $b)) {
                        continue;
                    }
                    if (!isset($pairMeta[$pk])) {
                        $pairMeta[$pk] = $score;
                    } else {
                        $pairMeta[$pk]['name_pct'] = $score['pct'];
                        $pairMeta[$pk]['tokens_a'] = $score['tokens_a'];
                        $pairMeta[$pk]['tokens_b'] = $score['tokens_b'];
                        $pairMeta[$pk]['overlap'] = $score['overlap'];
                        $pairMeta[$pk]['match_type'] = 'GSTIN + name';
                    }
                }
            }
        }

        $buckets = [];
        foreach ($merchants as $id => $m) {
            $buckets[$this->find($id)][] = $id;
        }

        $groups = [];
        foreach ($buckets as $ids) {
            $ids = array_values(array_unique($ids));
            if (count($ids) < 2) {
                continue;
            }
            $members = [];
            foreach ($ids as $id) {
                $members[] = $merchants[$id];
            }
            usort($members, function ($a, $b) {
                if ($b['filled'] !== $a['filled']) {
                    return $b['filled'] <=> $a['filled'];
                }
                $ag = $a['gstin_key'] !== '' ? 1 : 0;
                $bg = $b['gstin_key'] !== '' ? 1 : 0;
                if ($bg !== $ag) {
                    return $bg <=> $ag;
                }

                return $a['id'] <=> $b['id'];
            });
            $keep = $members[0];
            $outMembers = [];
            $bestType = 'Name';
            $groupGstin = $keep['gstin_key'];
            foreach ($members as $idx => $m) {
                if ($idx === 0) {
                    $outMembers[] = $this->publicMember($m, [
                        'is_keep' => true,
                        'match_type' => 'Keep',
                        'match_pct' => 100,
                        'tokens_a' => $m['tokens'],
                        'tokens_b' => $m['tokens'],
                        'overlap' => $m['tokens'],
                    ]);
                    continue;
                }
                $pk = $this->pairKey($keep['id'], $m['id']);
                $meta = isset($pairMeta[$pk]) ? $pairMeta[$pk] : $this->nameScore($keep, $m);
                if ($meta === null) {
                    $meta = [
                        'match_type' => 'Linked',
                        'pct' => 0,
                        'name_pct' => 0,
                        'tokens_a' => $keep['tokens'],
                        'tokens_b' => $m['tokens'],
                        'overlap' => array_values(array_intersect($keep['tokens'], $m['tokens'])),
                    ];
                }
                if (($meta['match_type'] ?? '') === 'GSTIN' || ($meta['match_type'] ?? '') === 'GSTIN + name') {
                    $bestType = $meta['match_type'];
                    if ($m['gstin_key'] !== '') {
                        $groupGstin = $m['gstin_key'];
                    }
                }
                $outMembers[] = $this->publicMember($m, [
                    'is_keep' => false,
                    'match_type' => $meta['match_type'] ?? 'Name',
                    'match_pct' => (int) ($meta['pct'] ?? $meta['name_pct'] ?? 0),
                    'tokens_a' => $meta['tokens_a'] ?? $keep['tokens'],
                    'tokens_b' => $meta['tokens_b'] ?? $m['tokens'],
                    'overlap' => $meta['overlap'] ?? [],
                ]);
            }
            $groups[] = [
                'keep_id' => $keep['id'],
                'match_type' => $bestType,
                'gstin' => $groupGstin,
                'size' => count($outMembers),
                'members' => $outMembers,
            ];
        }

        usort($groups, function ($a, $b) {
            $aw = strpos($a['match_type'], 'GSTIN') === 0 ? 1 : 0;
            $bw = strpos($b['match_type'], 'GSTIN') === 0 ? 1 : 0;
            if ($bw !== $aw) {
                return $bw <=> $aw;
            }
            if ($b['size'] !== $a['size']) {
                return $b['size'] <=> $a['size'];
            }

            return $a['keep_id'] <=> $b['keep_id'];
        });

        return $groups;
    }

    /**
     * Empty fields on $keep filled from $from. Existing keep values are not overwritten.
     *
     * @return array<string,mixed>
     */
    public function emptyFieldsFrom(Merchant $keep, Merchant $from): array
    {
        $fill = [];
        $map = [
            'stc_merchant_address' => $from->stc_merchant_address,
            'stc_merchant_contact_person' => $from->stc_merchant_contact_person,
            'stc_merchant_phone' => $from->stc_merchant_phone,
            'stc_merchant_email' => $from->stc_merchant_email,
            'stc_merchant_pan' => $from->stc_merchant_pan,
            'stc_merchant_gstin' => $from->stc_merchant_gstin,
            'stc_merchant_category' => $from->stc_merchant_category,
        ];
        foreach ($map as $column => $value) {
            $value = trim((string) ($value ?? ''));
            if ($this->isBlank($value)) {
                continue;
            }
            if ($this->isBlank($keep->{$column})) {
                $fill[$column] = $value;
            }
        }
        if ((int) $keep->stc_merchant_city_id === 0 && (int) $from->stc_merchant_city_id > 0) {
            $fill['stc_merchant_city_id'] = (int) $from->stc_merchant_city_id;
        }
        if ((int) $keep->stc_merchant_state_id === 0 && (int) $from->stc_merchant_state_id > 0) {
            $fill['stc_merchant_state_id'] = (int) $from->stc_merchant_state_id;
        }
        if ($this->isBlank($keep->stc_merchant_image) && !$this->isBlank($from->stc_merchant_image)) {
            $fill['stc_merchant_image'] = $from->stc_merchant_image;
        }

        return $fill;
    }

    /**
     * Concatenate specially_known_for from $from onto $keep. Null means no change.
     */
    public function concatenateKnownFor(Merchant $keep, Merchant $from)
    {
        $a = trim((string) ($keep->stc_merchant_specially_known_for ?? ''));
        $b = trim((string) ($from->stc_merchant_specially_known_for ?? ''));
        if ($this->isBlank($b)) {
            return null;
        }
        if ($this->isBlank($a)) {
            return $b;
        }
        if (mb_stripos($a, $b, 0, 'UTF-8') !== false) {
            return null;
        }
        $joined = $a . ' | ' . $b;
        if (mb_strlen($joined, 'UTF-8') > 255) {
            $joined = mb_substr($joined, 0, 255, 'UTF-8');
        }

        return $joined;
    }

    public function normalizeGstin($value): string
    {
        $v = strtoupper(trim((string) $value));
        $v = (string) preg_replace('/[\s\-]/', '', $v);
        if ($v === '' || in_array($v, ['NA', 'N/A', 'N.A.', 'N.A', 'NIL', 'NULL', '-', '--'], true)) {
            return '';
        }

        return $v;
    }

    /**
     * @return array<string,int>
     */
    public function adhocUsageCounts(): array
    {
        $rows = DB::table('stc_purchase_product_adhoc')
            ->selectRaw("UPPER(TRIM(stc_purchase_product_adhoc_source)) as src_key, COUNT(*) as c")
            ->whereNotNull('stc_purchase_product_adhoc_source')
            ->whereRaw("TRIM(stc_purchase_product_adhoc_source) <> ''")
            ->groupBy(DB::raw("UPPER(TRIM(stc_purchase_product_adhoc_source))"))
            ->get();

        $out = [];
        foreach ($rows as $row) {
            $key = trim((string) $row->src_key);
            if ($key === '') {
                continue;
            }
            $out[$key] = (int) $row->c;
        }

        return $out;
    }

    /**
     * @return array<int,int>
     */
    public function countsByMerchantId(string $table, string $column): array
    {
        $rows = DB::table($table)
            ->selectRaw($column . ' as mid, COUNT(*) as c')
            ->where($column, '>', 0)
            ->groupBy($column)
            ->get();
        $out = [];
        foreach ($rows as $row) {
            $out[(int) $row->mid] = (int) $row->c;
        }

        return $out;
    }

    /**
     * @param array<string,mixed> $m
     * @param array<string,mixed> $extra
     * @return array<string,mixed>
     */
    private function publicMember(array $m, array $extra): array
    {
        return [
            'id' => $m['id'],
            'name' => $m['name'],
            'city' => $m['city'],
            'state' => $m['state'],
            'category' => $m['category'],
            'contact' => $m['contact'],
            'phone' => $m['phone'],
            'gstin' => $m['gstin'],
            'pan' => $m['pan'],
            'address' => $m['address'],
            'is_keep' => !empty($extra['is_keep']),
            'match_type' => $extra['match_type'],
            'match_pct' => (int) $extra['match_pct'],
            'tokens' => $extra['tokens_b'] ?? $m['tokens'],
            'keep_tokens' => $extra['tokens_a'] ?? [],
            'overlap' => $extra['overlap'] ?? [],
            'adhoc_uses' => (int) ($m['adhoc_uses'] ?? 0),
            'po_uses' => (int) ($m['po_uses'] ?? 0),
            'trading_uses' => (int) ($m['trading_uses'] ?? 0),
            'known_for' => (string) ($m['known_for'] ?? ''),
        ];
    }

    /**
     * @param array<string,mixed> $a
     * @param array<string,mixed> $b
     * @return array<string,mixed>
     */
    private function gstinPairMeta(array $a, array $b, string $gstinKey): array
    {
        $name = $this->nameScore($a, $b);
        $meta = [
            'match_type' => 'GSTIN',
            'pct' => 100,
            'name_pct' => $name ? (int) $name['pct'] : 0,
            'gstin' => $gstinKey,
            'tokens_a' => $a['tokens'],
            'tokens_b' => $b['tokens'],
            'overlap' => array_values(array_intersect($a['tokens'], $b['tokens'])),
        ];
        if ($name && (int) $name['pct'] >= 50) {
            $meta['match_type'] = 'GSTIN + name';
        }

        return $meta;
    }

    /**
     * @param array<string,mixed> $a
     * @param array<string,mixed> $b
     * @return array<string,mixed>|null
     */
    private function nameScore(array $a, array $b)
    {
        $ta = $a['tokens'];
        $tb = $b['tokens'];
        if ($ta === [] || $tb === []) {
            return null;
        }
        $overlap = array_values(array_intersect($ta, $tb));
        $strongOverlap = array_values(array_intersect($a['strong'], $b['strong']));
        $shorter = min(count($ta), count($tb));
        $tokenPct = $shorter > 0 ? (int) round(100 * count($overlap) / $shorter) : 0;
        $sim = 0.0;
        similar_text($a['norm'], $b['norm'], $sim);
        $pct = (int) max($tokenPct, round($sim));

        $ok = ($strongOverlap !== [] && (count($overlap) >= 2 || $tokenPct >= 60))
            || ($sim >= 82 && count($overlap) >= 1);
        if (!$ok) {
            return null;
        }

        return [
            'match_type' => 'Name',
            'pct' => $pct,
            'name_pct' => $pct,
            'tokens_a' => $ta,
            'tokens_b' => $tb,
            'overlap' => $overlap,
        ];
    }

    /**
     * @return array<int,string>
     */
    public function nameTokens(string $name): array
    {
        $n = strtoupper(trim($name));
        $n = str_replace(['&', '.', ',', '-', '/', '\\', "'", '"', '(', ')', '_', '`'], ' ', $n);
        $n = preg_replace('/\bM\s*S\b/', ' ', $n);
        $n = preg_replace('/\b(MS|MESSRS|PVT|PRIVATE|LIMITED|LTD|LLP|CO|COMPANY|COMPANIES)\b/', ' ', (string) $n);
        $n = preg_replace('/\s+/', ' ', (string) $n);
        $parts = preg_split('/\s+/', trim((string) $n), -1, PREG_SPLIT_NO_EMPTY);
        $out = [];
        foreach ((array) $parts as $w) {
            if (strlen($w) < 2) {
                continue;
            }
            if (isset($out[$w])) {
                continue;
            }
            $out[$w] = $w;
        }

        return array_values($out);
    }

    /**
     * @param array<int,string> $tokens
     * @return array<int,string>
     */
    private function strongTokens(array $tokens): array
    {
        $weak = [
            'AGENCY' => true, 'AGENCIES' => true, 'ENTERPRISE' => true, 'ENTERPRISES' => true,
            'TRADER' => true, 'TRADERS' => true, 'TRADING' => true, 'SUPPLIER' => true,
            'SUPPLIERS' => true, 'STORE' => true, 'STORES' => true, 'INDUSTRY' => true,
            'INDUSTRIES' => true, 'WORKS' => true, 'SALES' => true, 'SERVICE' => true,
            'SERVICES' => true, 'CORPORATION' => true, 'CORP' => true, 'INDIA' => true,
            'BROS' => true, 'BROTHERS' => true, 'CONCERN' => true, 'HOUSE' => true,
            'MART' => true, 'NEW' => true, 'OLD' => true, 'THE' => true, 'AND' => true,
            'FOR' => true, 'OF' => true, 'SHOP' => true, 'SHOPS' => true,
        ];
        $out = [];
        foreach ($tokens as $t) {
            if (isset($weak[$t]) || strlen($t) < 3) {
                continue;
            }
            $out[] = $t;
        }

        return $out;
    }

    private function filledScore($row): int
    {
        $n = 0;
        foreach (['stc_merchant_address', 'stc_merchant_contact_person', 'stc_merchant_phone', 'stc_merchant_email', 'stc_merchant_pan', 'stc_merchant_gstin', 'stc_merchant_category'] as $col) {
            if (!$this->isBlank($row->{$col} ?? '')) {
                $n++;
            }
        }
        if ((int) ($row->stc_merchant_city_id ?? 0) > 0) {
            $n++;
        }
        if ((int) ($row->stc_merchant_state_id ?? 0) > 0) {
            $n++;
        }

        return $n;
    }

    private function isBlank($value): bool
    {
        $v = trim((string) ($value ?? ''));
        if ($v === '') {
            return true;
        }

        return in_array(strtoupper($v), ['NA', 'N/A', 'N.A.', 'N.A', 'NIL', 'NULL', '-', '--'], true);
    }

    private function hasConflictingGstin(array $a, array $b): bool
    {
        return $a['gstin_key'] !== ''
            && $b['gstin_key'] !== ''
            && $a['gstin_key'] !== $b['gstin_key'];
    }

    private function pairKey(int $a, int $b): string
    {
        return $a < $b ? $a . '-' . $b : $b . '-' . $a;
    }

    private function find(int $x): int
    {
        if (!isset($this->parent[$x])) {
            $this->parent[$x] = $x;
        }
        if ($this->parent[$x] !== $x) {
            $this->parent[$x] = $this->find($this->parent[$x]);
        }

        return $this->parent[$x];
    }

    private function union(int $a, int $b): bool
    {
        $ra = $this->find($a);
        $rb = $this->find($b);
        if ($ra === $rb) {
            return true;
        }
        $ga = isset($this->rootGstins[$ra]) ? $this->rootGstins[$ra] : [];
        $gb = isset($this->rootGstins[$rb]) ? $this->rootGstins[$rb] : [];
        $merged = $ga + $gb;
        if (count($merged) > 1) {
            return false;
        }
        $this->parent[$rb] = $ra;
        $this->rootGstins[$ra] = $merged;
        unset($this->rootGstins[$rb]);

        return true;
    }
}
