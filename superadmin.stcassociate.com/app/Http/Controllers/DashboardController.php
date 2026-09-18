<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function show()
    {
        $reqStatusLabels = [
            1 => 'Process',
            2 => 'Passed',
            3 => 'Procurement',
            4 => 'Completed',
        ];
        $adhocStatusLabels = [
            1 => 'Stock',
            2 => 'Dispatched',
            3 => 'Pending',
            4 => 'Approved',
            5 => 'Rejected',
        ];

        $userCounts = [
            ['label' => 'Site', 'count' => $this->tableCount('stc_cust_pro_supervisor'), 'url' => url('/users')],
            ['label' => 'School', 'count' => $this->tableCount('stc_school'), 'url' => url('/users')],
            ['label' => 'Managers', 'count' => $this->tableCount('stc_agents'), 'url' => url('/users')],
            ['label' => 'User admin', 'count' => $this->tableCount('stc_user'), 'url' => url('/users')],
            ['label' => 'Trading', 'count' => $this->tableCount('stc_trading_user'), 'url' => url('/users')],
            ['label' => 'Electronics', 'count' => $this->tableCount('stc_electronics_user'), 'url' => url('/users')],
            ['label' => 'Groceries', 'count' => $this->tableCount('stc_groceries_user'), 'url' => url('/users')],
            ['label' => 'Super admin', 'count' => $this->tableCount('users'), 'url' => url('/users/admin')],
        ];

        $data = [
            'page_title' => 'Dashboard',
            'counts' => [
                'products' => $this->tableCount('stc_product'),
                'products_active' => $this->tableCountWhere('stc_product', 'stc_product_avail', 1),
                'merchants' => $this->tableCount('stc_merchant'),
                'projects' => $this->tableCount('stc_cust_project'),
                'requisitions' => $this->tableCount('stc_cust_super_requisition_list'),
                'poadhoc' => $this->tableCount('stc_purchase_product_adhoc'),
                'equipment' => $this->tableCount('equipment_details'),
                'users' => array_sum(array_column($userCounts, 'count')),
                'std' => $this->tableCount('stc_status_down_list'),
                'tools' => $this->tableCount('stc_tooldetails'),
                'gld' => $this->tableCount('gld_challan'),
                'inventory' => $this->tableCount('stc_item_inventory'),
            ],
            'user_counts' => $userCounts,
            'req_status' => $this->groupedCounts(
                'stc_cust_super_requisition_list',
                'stc_cust_super_requisition_list_status',
                $reqStatusLabels
            ),
            'adhoc_status' => $this->groupedCounts(
                'stc_purchase_product_adhoc',
                'stc_purchase_product_adhoc_status',
                $adhocStatusLabels
            ),
            'monthly_requisitions' => $this->monthlyCounts(
                'stc_cust_super_requisition_list',
                'stc_cust_super_requisition_list_date',
                12
            ),
            'recent_requisitions' => $this->recentRequisitions(10),
        ];

        return view('pages.dashboard', $data);
    }

    private function tableCount($table)
    {
        try {
            if (! Schema::hasTable($table)) {
                return 0;
            }
            return (int) DB::table($table)->count();
        } catch (\Throwable $e) {
            return 0;
        }
    }

    private function tableCountWhere($table, $column, $value)
    {
        try {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, $column)) {
                return 0;
            }
            return (int) DB::table($table)->where($column, $value)->count();
        } catch (\Throwable $e) {
            return 0;
        }
    }

    private function groupedCounts($table, $column, array $labels)
    {
        $out = [];
        foreach ($labels as $key => $label) {
            $out[(string) $key] = ['label' => $label, 'count' => 0];
        }

        try {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, $column)) {
                return array_values($out);
            }

            $rows = DB::table($table)
                ->select($column, DB::raw('COUNT(*) as total'))
                ->groupBy($column)
                ->get();

            foreach ($rows as $row) {
                $key = (string) $row->{$column};
                $count = (int) $row->total;
                if (isset($out[$key])) {
                    $out[$key]['count'] = $count;
                } else {
                    $out[$key] = ['label' => $key !== '' ? $key : 'Other', 'count' => $count];
                }
            }
        } catch (\Throwable $e) {
            // keep zeros
        }

        return array_values($out);
    }

    private function monthlyCounts($table, $dateColumn, $months = 12)
    {
        $labels = [];
        $values = [];
        $map = [];
        $start = Carbon::now()->startOfMonth()->subMonths($months - 1);

        for ($i = 0; $i < $months; $i++) {
            $month = $start->copy()->addMonths($i);
            $key = $month->format('Y-m');
            $map[$key] = 0;
            $labels[] = $month->format('M Y');
        }

        try {
            if (Schema::hasTable($table) && Schema::hasColumn($table, $dateColumn)) {
                $rows = DB::table($table)
                    ->selectRaw("DATE_FORMAT({$dateColumn}, '%Y-%m') as ym, COUNT(*) as total")
                    ->where($dateColumn, '>=', $start->toDateTimeString())
                    ->groupBy(DB::raw("DATE_FORMAT({$dateColumn}, '%Y-%m')"))
                    ->orderBy('ym')
                    ->get();

                foreach ($rows as $row) {
                    if (isset($map[$row->ym])) {
                        $map[$row->ym] = (int) $row->total;
                    }
                }
            }
        } catch (\Throwable $e) {
            // keep zeros
        }

        foreach ($map as $count) {
            $values[] = $count;
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    private function recentRequisitions($limit = 10)
    {
        try {
            if (! Schema::hasTable('stc_cust_super_requisition_list')) {
                return collect();
            }

            return DB::table('stc_cust_super_requisition_list as r')
                ->leftJoin('stc_cust_project as p', 'p.stc_cust_project_id', '=', 'r.stc_cust_super_requisition_list_project_id')
                ->leftJoin('stc_cust_pro_supervisor as s', 's.stc_cust_pro_supervisor_id', '=', 'r.stc_cust_super_requisition_list_super_id')
                ->select(
                    'r.stc_cust_super_requisition_list_id as id',
                    'r.stc_cust_super_requisition_list_date as date',
                    'r.stc_cust_super_requisition_list_status as status',
                    'p.stc_cust_project_title as project',
                    's.stc_cust_pro_supervisor_fullname as supervisor'
                )
                ->orderByDesc('r.stc_cust_super_requisition_list_id')
                ->limit($limit)
                ->get();
        } catch (\Throwable $e) {
            return collect();
        }
    }
}
