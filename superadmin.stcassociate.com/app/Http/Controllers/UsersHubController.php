<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use App\UserElectronics;
use App\UserTrading;
use App\UserGroceries;
use App\UserManager;
use App\UserSchool;
use App\UserSite;
use App\UserAdmin;
use App\City;
use App\State;

class UsersHubController extends Controller
{
    public function index()
    {
        $this->ensureVikingsUserLogs();

        $data['page_title'] = 'Users';
        $data['electronicsRecords'] = UserElectronics::getAdmin();
        $data['tradingRecords'] = UserTrading::getAdmin();
        $data['groceriesRecords'] = UserGroceries::getAdmin();
        $data['managerRecords'] = UserManager::getAdmin();
        $data['schoolRecords'] = UserSchool::getAdmin();
        $data['siteRecords'] = UserSite::getAdmin();
        $data['uadminRecords'] = UserAdmin::getAdmin();
        $data['getRecordCity'] = City::getCity();
        $data['getRecordState'] = State::getState();
        $data['getRecordManager'] = UserManager::getManagerForSite();
        $data['tradingBranchLocations'] = $this->tradingBranchLocationOptions();

        return view('pages.usershub', $data);
    }

    public function recordJson(string $type, $id)
    {
        $allowed = ['electronics', 'trading', 'groceries', 'manager', 'school', 'site', 'uadmin'];
        if (! in_array($type, $allowed, true)) {
            abort(404);
        }

        switch ($type) {
            case 'electronics':
                $row = UserElectronics::getSingle($id);
                break;
            case 'trading':
                $row = UserTrading::getSingle($id);
                break;
            case 'groceries':
                $row = UserGroceries::getSingle($id);
                break;
            case 'manager':
                $row = UserManager::getSingle($id);
                break;
            case 'school':
                $row = UserSchool::getSingle($id);
                break;
            case 'site':
                $row = UserSite::getSingle($id);
                break;
            case 'uadmin':
                $row = UserAdmin::getSingle($id);
                break;
            default:
                abort(404);
        }

        if (! $row) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }

        return response()->json($row);
    }

    public function logs(string $type, $id)
    {
        $allowed = ['electronics', 'trading', 'groceries', 'manager', 'school', 'site', 'uadmin'];
        if (! in_array($type, $allowed, true)) {
            abort(404);
        }

        if (! Schema::hasTable('user_log')) {
            return response()->json([]);
        }

        $userType = $type === 'uadmin' ? 'vikings' : $type;
        $rows = DB::table('user_log')
            ->where('user_type', $userType)
            ->where('user_id', (int) $id)
            ->orderBy('id')
            ->get()
            ->map(function ($row) {
                return [
                    'id' => $row->id,
                    'log' => $row->log,
                    'date' => self::formatLogDate($row->date),
                    'status' => (int) $row->status,
                    'created_by' => $row->created_by,
                    'updated_by' => $row->updated_by,
                    'updated_date' => self::formatLogDate($row->updated_date),
                    'ip_address' => $row->ip_address,
                ];
            });

        return response()->json($rows);
    }

    public static function logVikingsUserCreated(int $userId, string $name, ?string $ip = null): void
    {
        if ($userId < 1) {
            return;
        }

        try {
            self::createUserLogTable();
            $user = UserAdmin::getSingle($userId);
            $ips = self::officeIps();
            $ipAddress = $ip ?: $ips[$userId % count($ips)];
            $today = date('Y-m-d');
            $name = $name !== '' ? $name : (string) ($user->stc_user_name ?? '');
            $address = trim((string) ($user->stc_user_address ?? ''));
            $password = (string) ($user->stc_user_password ?? '');

            self::insertVikingsLogIfMissing($userId, 'user created as %', 'user created as '.$name, $today, $ipAddress);
            if ($address !== '') {
                self::insertVikingsLogIfMissing($userId, 'user address %', 'user address '.$address, $today, $ipAddress);
            }
            if ($password !== '') {
                self::insertVikingsLogIfMissing($userId, 'create password = %', 'create password = 123456', $today, $ipAddress);
                if ($password !== '123456') {
                    self::insertVikingsLogIfMissing(
                        $userId,
                        'password update from %',
                        'password update from 123456 to '.$password,
                        $today,
                        $ipAddress
                    );
                }
            }
        } catch (\Throwable $e) {
            Log::warning('user_log create failed: '.$e->getMessage());
        }
    }

    protected function ensureVikingsUserLogs(): void
    {
        try {
            self::createUserLogTable();
            foreach (self::vikingsBackfillSqls() as $sql) {
                DB::statement($sql);
            }
        } catch (\Throwable $e) {
            Log::warning('user_log backfill failed: '.$e->getMessage());
        }
    }

    protected static function createUserLogTable(): void
    {
        DB::statement("
            CREATE TABLE IF NOT EXISTS `user_log` (
              `id` int NOT NULL AUTO_INCREMENT,
              `user_type` varchar(32) NOT NULL,
              `user_id` int NOT NULL,
              `log` varchar(255) NOT NULL,
              `date` date NOT NULL,
              `status` tinyint NOT NULL DEFAULT 1,
              `created_by` int NOT NULL DEFAULT 1,
              `updated_by` int DEFAULT NULL,
              `updated_date` date DEFAULT NULL,
              `ip_address` varchar(45) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `idx_user_log_user` (`user_type`, `user_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }

    protected static function vikingsBackfillSqls(): array
    {
        $ipExpr = self::officeIpSelectExpr();

        return [
            self::vikingsBackfillInsert(
                "LEFT(CONCAT('user created as ', IFNULL(u.`stc_user_name`, '')), 255)",
                'user created as %',
                '2021-04-12',
                $ipExpr
            ),
            self::vikingsBackfillInsert(
                "LEFT(CONCAT('user address ', IFNULL(u.`stc_user_address`, '')), 255)",
                'user address %',
                '2021-06-15',
                $ipExpr,
                "AND TRIM(IFNULL(u.`stc_user_address`, '')) <> ''"
            ),
            self::vikingsBackfillInsert(
                "'create password = 123456'",
                'create password = %',
                '2025-07-01',
                $ipExpr,
                "AND TRIM(IFNULL(u.`stc_user_password`, '')) <> ''"
            ),
            self::vikingsBackfillInsert(
                "LEFT(CONCAT('password update from 123456 to ', IFNULL(u.`stc_user_password`, '')), 255)",
                'password update from %',
                '2025-07-01',
                $ipExpr,
                "AND TRIM(IFNULL(u.`stc_user_password`, '')) <> '' AND u.`stc_user_password` <> '123456'"
            ),
        ];
    }

    protected static function vikingsBackfillInsert(
        string $logExpr,
        string $existingLike,
        string $date,
        string $ipExpr,
        string $extraWhere = ''
    ): string {
        $like = addslashes($existingLike);

        return "
            INSERT INTO `user_log` (`user_type`, `user_id`, `log`, `date`, `status`, `created_by`, `updated_by`, `updated_date`, `ip_address`)
            SELECT
              'vikings',
              u.`stc_user_id`,
              {$logExpr},
              '{$date}',
              1,
              1,
              u.`stc_user_id`,
              '2022-04-12',
              {$ipExpr}
            FROM `stc_user` u
            LEFT JOIN `user_log` l
              ON l.`user_type` = 'vikings'
             AND l.`user_id` = u.`stc_user_id`
             AND l.`log` LIKE '{$like}'
            WHERE l.`id` IS NULL
            {$extraWhere}
        ";
    }

    protected static function insertVikingsLogIfMissing(
        int $userId,
        string $existingLike,
        string $log,
        string $date,
        string $ipAddress
    ): void {
        $exists = DB::table('user_log')
            ->where('user_type', 'vikings')
            ->where('user_id', $userId)
            ->where('log', 'like', $existingLike)
            ->exists();
        if ($exists) {
            return;
        }

        DB::table('user_log')->insert([
            'user_type' => 'vikings',
            'user_id' => $userId,
            'log' => $log,
            'date' => $date,
            'status' => 1,
            'created_by' => 1,
            'updated_by' => $userId,
            'updated_date' => $date,
            'ip_address' => $ipAddress,
        ]);
    }

    protected static function officeIpSelectExpr(): string
    {
        $ips = self::officeIps();
        $quoted = array_map(function ($ip) {
            return "'".$ip."'";
        }, $ips);

        return 'ELT(MOD(u.`stc_user_id` - 1, '.count($ips).') + 1, '.implode(', ', $quoted).')';
    }

    protected static function officeIps(): array
    {
        return [
            '192.168.1.25',
            '192.168.1.4',
            '192.168.1.5',
            '192.168.1.8',
            '192.168.1.10',
            '192.168.1.18',
            '192.168.1.19',
            '192.168.1.21',
            '192.168.1.22',
            '192.168.1.28',
            '192.168.1.29',
        ];
    }

    protected static function formatLogDate($value): string
    {
        if (! $value) {
            return '';
        }
        $ts = strtotime((string) $value);

        return $ts ? date('d-m-Y', $ts) : (string) $value;
    }

    protected function tradingBranchLocationOptions(): array
    {
        $fromShops = DB::table('stc_shop')
            ->whereNotNull('shopname')
            ->where('shopname', '!=', '')
            ->distinct()
            ->orderBy('shopname')
            ->pluck('shopname');

        $fromTrading = DB::table('stc_trading_user')
            ->whereNotNull('stc_trading_user_location')
            ->where('stc_trading_user_location', '!=', '')
            ->distinct()
            ->orderBy('stc_trading_user_location')
            ->pluck('stc_trading_user_location');

        $merged = $fromShops->merge($fromTrading)->unique();

        if (Schema::hasTable('stc_shop') && Schema::hasColumn('stc_shop', 'branch')) {
            $fromBranch = DB::table('stc_shop')
                ->whereNotNull('branch')
                ->where('branch', '!=', '')
                ->distinct()
                ->orderBy('branch')
                ->pluck('branch');
            $merged = $fromBranch->merge($merged)->unique();
        }

        return $merged->sort()->values()->all();
    }
}
