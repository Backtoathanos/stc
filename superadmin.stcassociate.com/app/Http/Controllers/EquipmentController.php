<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Equipment;
use Illuminate\Support\Facades\DB;

class EquipmentController extends Controller
{
    public function show(){
        $data['page_title'] = "Status Down List";
        return view('pages.equipment', $data);
    }

    // Show through AJAX
    public function list(Request $request)
    {
        ## Read value
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords = Equipment::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Equipment::select('count(*) as allcount')
            ->where('id', 'like', '%' . $searchValue . '%')
            ->orwhere('equipment_name', 'like', '%' . $searchValue . '%')
            ->orwhere('area', 'like', '%' . $searchValue . '%')
            ->orwhere('model_no', 'like', '%' . $searchValue . '%')
            ->orwhere('slno', 'like', '%' . $searchValue . '%')
            ->orwhere('equipment_no', 'like', '%' . $searchValue . '%')
            ->orwhere('stc_cust_project_title', 'like', '%' . $searchValue . '%')
            ->leftjoin('stc_cust_project','stc_cust_project_id','=','equipment_details.location')
            ->leftjoin('stc_cust_pro_supervisor','stc_cust_pro_supervisor.stc_cust_pro_supervisor_id','=','equipment_details.created_by')
            ->select('equipment_details.*', 'stc_cust_project.stc_cust_project_title', 'stc_cust_pro_supervisor.stc_cust_pro_supervisor_fullname')
            ->count();

        // Fetch records
        $records = Equipment::orderBy($columnName, $columnSortOrder)
            ->where('id', 'like', '%' . $searchValue . '%')
            ->orwhere('equipment_name', 'like', '%' . $searchValue . '%')
            ->orwhere('area', 'like', '%' . $searchValue . '%')
            ->orwhere('model_no', 'like', '%' . $searchValue . '%')
            ->orwhere('slno', 'like', '%' . $searchValue . '%')
            ->orwhere('equipment_no', 'like', '%' . $searchValue . '%')
            ->orwhere('stc_cust_project_title', 'like', '%' . $searchValue . '%')
            ->leftjoin('stc_cust_project','stc_cust_project_id','=','equipment_details.location')
            ->leftjoin('stc_cust_pro_supervisor','stc_cust_pro_supervisor.stc_cust_pro_supervisor_id','=','equipment_details.created_by')
            ->leftjoin('stc_status_down_list_department','stc_status_down_list_department_id','=','equipment_details.department')
            ->select('equipment_details.*', 'stc_cust_project.stc_cust_project_title', 'stc_cust_pro_supervisor.stc_cust_pro_supervisor_fullname', 'stc_status_down_list_department.stc_status_down_list_department_dept')
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();
        
        foreach ($records as $record) {
            $id = $record->id;
            $actionData = '
                <a href="javascript:void(0)" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete-modal" onclick=$("#delete_id").val("'.$id.'")><i class="fas fa-trash" title="Delete"></i></a>
            '; 

            $data_arr[] = array(
                'id' => $record->id,
                'location' => $record->stc_cust_project_title,
                'stc_status_down_list_department_dept' => $record->stc_status_down_list_department_dept,
                'area' => $record->area,
                'model_no' => $record->model_no,
                'capacity' => $record->capacity,
                'equipment_name' => $record->equipment_name,
                'unit_no' => $record->unit_no,
                'stc_cust_pro_supervisor_fullname' => $record->stc_cust_pro_supervisor_fullname,
                'created_date' => $record->created_date,
                'ahu_filter_qty' => $record->ahu_filter_qty,
                'ahu_filter_size' => $record->ahu_filter_size,
                'ahu_filter_type' => $record->ahu_filter_type,
                'ahu_make_name' => $record->ahu_make_name,
                'ahu_v_belt_qty' => $record->ahu_v_belt_qty,
                'ahu_v_belt_size' => $record->ahu_v_belt_size,
                'bearing_size' => $record->bearing_size,
                'blower_bearing_size' => $record->blower_bearing_size,
                'blower_flywheel_size' => $record->blower_flywheel_size,
                'compressor_qty' => $record->compressor_qty,
                'control' => $record->control,
                'coupling_size' => $record->coupling_size,
                'coupling_type' => $record->coupling_type,
                'current_rating_max' => $record->current_rating_max,
                'delta_t' => $record->delta_t,
                'delta_p' => $record->delta_p,
                'each_of_capacity' => $record->each_of_capacity,
                'equipment_serial_no' => $record->equipment_serial_no,
                'fan_blade_qty' => $record->fan_blade_qty,
                'fan_blade_size' => $record->fan_blade_size,
                'filter_qty' => $record->filter_qty,
                'filter_size' => $record->filter_size,
                'header_size' => $record->header_size,
                'inlet_pressure' => $record->inlet_pressure,
                'inlet_temp' => $record->inlet_temp,
                'make_name' => $record->make_name,
                'max_fuse_rating' => $record->max_fuse_rating,
                'max_load' => $record->max_load,
                'min_fuse_rating' => $record->min_fuse_rating,
                'min_load' => $record->min_load,
                'motor_bearing_size' => $record->motor_bearing_size,
                'motor_capacity' => $record->motor_capacity,
                'motor_current_rating' => $record->motor_current_rating,
                'motor_make_name' => $record->motor_make_name,
                'motor_pulley_size' => $record->motor_pulley_size,
                'motor_rpm' => $record->motor_rpm,
                'motor_voltage_rating' => $record->motor_voltage_rating,
                'outlet_pressure' => $record->outlet_pressure,
                'outlet_temp' => $record->outlet_temp,
                'power_factor' => $record->power_factor,
                'pump_head' => $record->pump_head,
                'pump_make_name' => $record->pump_make_name,
                'refrigerant_type' => $record->refrigerant_type,
                'tyre_size' => $record->tyre_size,
                'v_belt_qty' => $record->v_belt_qty,
                'v_belt_size' => $record->v_belt_size,
                "actionData" => $actionData
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        return json_encode($response);
    }

    // Delete record
    public function delete(Request $request){
        $delete = Equipment::destroy($request->id);
        if($delete){
            return response()->json(['success' => true, 'message' => 'Record deleted successfully!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Record deletion failed!']);
        }
    }

    // Get equipment logs - same format as stc_get_equipmentdetails_logs
    public function logs(Request $request){
        try {
            $equipmentId = $request->get('equipment_id');
            $fromDate = $request->get('from_date');
            $toDate = $request->get('to_date');
            $search = $request->get('search', '');
            
            // Pagination parameters
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 25);
            
            // Sorting parameters
            $sortColumn = $request->get('sort_column', 'created_date');
            $sortDirection = strtolower($request->get('sort_direction', 'desc'));
            
            // Validate sort direction
            if (!in_array($sortDirection, ['asc', 'desc'])) {
                $sortDirection = 'desc';
            }

            // Build base query
            $query = DB::table('equipment_details_log as EDL')
                ->join('equipment_details as ED', 'EDL.equipment_details_id', '=', 'ED.id')
                ->leftJoin('stc_status_down_list_department as SDLD', 'SDLD.stc_status_down_list_department_id', '=', 'ED.department')
                ->join('stc_cust_pro_supervisor as S', 'EDL.created_by', '=', 'S.stc_cust_pro_supervisor_id')
                ->select(
                    'EDL.*',
                    'ED.unit_no',
                    'ED.department',
                    'ED.area',
                    'ED.sub_location',
                    'ED.model_no',
                    'ED.equipment_name',
                    'ED.slno',
                    'ED.equipment_no',
                    'ED.equipment_type',
                    'SDLD.stc_status_down_list_department_id',
                    'SDLD.stc_status_down_list_department_location',
                    'SDLD.stc_status_down_list_department_dept',
                    'EDL.creator_name as creator'
                );

            // Apply filters
            if (!empty($equipmentId)) {
                $query->where('EDL.equipment_details_id', $equipmentId);
            }

            if (!empty($fromDate)) {
                $query->whereDate('EDL.created_date', '>=', $fromDate);
            }

            if (!empty($toDate)) {
                $query->whereDate('EDL.created_date', '<=', $toDate);
            }

            // Apply search filter
            if (!empty($search)) {
                $searchTerm = '%' . $search . '%';
                $query->where(function($q) use ($searchTerm) {
                    $q->where('SDLD.stc_status_down_list_department_location', 'like', $searchTerm)
                      ->orWhere('SDLD.stc_status_down_list_department_dept', 'like', $searchTerm)
                      ->orWhere('ED.sub_location', 'like', $searchTerm)
                      ->orWhere('ED.slno', 'like', $searchTerm)
                      ->orWhere('ED.equipment_no', 'like', $searchTerm)
                      ->orWhere('ED.equipment_type', 'like', $searchTerm)
                      ->orWhere('ED.area', 'like', $searchTerm)
                      ->orWhere('ED.model_no', 'like', $searchTerm)
                      ->orWhere('ED.equipment_name', 'like', $searchTerm)
                      ->orWhere('ED.unit_no', 'like', $searchTerm);
                });
            }

            // Get total count for pagination
            $totalRecords = $query->count();
            
            // Apply sorting
            $allowedSortColumns = ['created_date', 'voltage', 'equipment_name', 'unit_no', 'area', 'model_no'];
            if (in_array($sortColumn, $allowedSortColumns)) {
                if ($sortColumn == 'created_date') {
                    $query->orderBy('EDL.created_date', $sortDirection);
                } else {
                    $query->orderBy('ED.' . $sortColumn, $sortDirection);
                }
            } else {
                $query->orderBy('EDL.created_date', 'DESC');
            }

            // Apply pagination
            $offset = ($page - 1) * $perPage;
            $logs = $query->skip($offset)->take($perPage)->get();
            
            $totalPages = ceil($totalRecords / $perPage);

            if ($logs->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'html' => '<p class="text-center">No log entries found.</p>'
                ]);
            }

            $rowsHtml = '';
            $maxCompCnt = 0;
            $maxCompCntA = 0;
            $maxCompCntB = 0;
            $maxCompCntC = 0;
            $dataRows = [];

            foreach ($logs as $row) {
                // Get compressor data
                $comps = DB::table('equipment_details_log_comp')
                    ->where('equipment_details_log_id', $row->id)
                    ->get();

                $compCols = [];
                $compCnt = $comps->count();
                $maxCompCnt = max($maxCompCnt, $compCnt);

                foreach ($comps as $cr) {
                    $compCols[] = [
                        $cr->suction_pr_psig ?? '',
                        $cr->disc_pr ?? '',
                        $cr->disc_temp_degC ?? '',
                        $cr->dsh ?? '',
                        $cr->oil_level ?? '',
                        $cr->comp_load ?? '',
                        $cr->comp_amp ?? ''
                    ];
                }

                // Get CD water pump data
                $cd_pumps = [];
                $cdPumpsData = DB::table('equipment_details_log_cd_waterpump')
                    ->where('equipment_details_log_id', $row->id)
                    ->select('numb', 'amp')
                    ->get();
                $cdPumpCnt = $cdPumpsData->count();
                $maxCompCntA = max($maxCompCntA, $cdPumpCnt);
                foreach ($cdPumpsData as $cd) {
                    $cd_pumps[] = [$cd->numb ?? '', $cd->amp ?? ''];
                }

                // Get CH water pump data
                $ch_pumps = [];
                $chPumpsData = DB::table('equipment_details_log_ch_waterpump')
                    ->where('equipment_details_log_id', $row->id)
                    ->select('numb', 'amp')
                    ->get();
                $chPumpCnt = $chPumpsData->count();
                $maxCompCntB = max($maxCompCntB, $chPumpCnt);
                foreach ($chPumpsData as $ch) {
                    $ch_pumps[] = [$ch->numb ?? '', $ch->amp ?? ''];
                }

                // Get cooling tower data
                $ctowers = [];
                $ctData = DB::table('equipment_details_log_coolingtower')
                    ->where('equipment_details_log_id', $row->id)
                    ->select('numb', 'amp')
                    ->get();
                $ctCnt = $ctData->count();
                $maxCompCntC = max($maxCompCntC, $ctCnt);
                foreach ($ctData as $ct) {
                    $ctowers[] = [$ct->numb ?? '', $ct->amp ?? ''];
                }

                $dataRows[] = [
                    'date' => date('d-m-Y', strtotime($row->created_date)),
                    'time' => date('h:i A', strtotime($row->created_date)),
                    'department_location' => $row->stc_status_down_list_department_location ?? '',
                    'department_dept' => $row->stc_status_down_list_department_dept ?? '',
                    'area' => $row->area ?? '',
                    'sub_location' => $row->sub_location ?? '',
                    'model_no' => $row->model_no ?? '',
                    'equipment_name' => $row->equipment_name ?? '',
                    'slno' => $row->slno ?? '',
                    'unit' => $row->unit_no ?? '',
                    'equipment_no' => $row->equipment_no ?? '',
                    'equipment_type' => $row->equipment_type ?? '',
                    'voltage' => $row->voltage ?? '',
                    'comps' => $compCols,
                    'chw' => [
                        $row->chw_inlet_temp ?? '',
                        $row->chw_outlet_temp ?? '',
                        $row->chw_inlet_pr ?? '',
                        $row->chw_outlet_pr ?? ''
                    ],
                    'cow' => [
                        $row->cow_inlet_temp ?? '',
                        $row->cow_outlet_temp ?? '',
                        $row->cow_inlet_pr ?? '',
                        $row->cow_outlet_pr ?? ''
                    ],
                    'cd_pumps' => $cd_pumps,
                    'ch_pumps' => $ch_pumps,
                    'ctowers' => $ctowers,
                    'creator' => $row->creator ?? ''
                ];
            }

            // Build header with sorting
            $getSortIcon = function($col) use ($sortColumn, $sortDirection) {
                if ($sortColumn == $col) {
                    return $sortDirection == 'asc' ? ' <i class="fas fa-sort-up"></i>' : ' <i class="fas fa-sort-down"></i>';
                }
                return ' <i class="fas fa-sort text-muted"></i>';
            };
            
            $getSortClass = function($col) use ($sortColumn) {
                return $sortColumn == $col ? 'sortable-header active' : 'sortable-header';
            };
            
            $thead = '<thead><tr>
                    <th class="text-center sortable-header ' . ($sortColumn == 'created_date' ? 'active' : '') . '" rowspan="2" data-sort="created_date" style="cursor: pointer;">DATE' . $getSortIcon('created_date') . '</th>
                    <th class="text-center" rowspan="2">TIME</th>
                    <th class="text-center" rowspan="2">DEPT LOCATION</th>
                    <th class="text-center" rowspan="2">DEPT NAME</th>
                    <th class="text-center sortable-header ' . ($sortColumn == 'area' ? 'active' : '') . '" rowspan="2" data-sort="area" style="cursor: pointer;">AREA' . $getSortIcon('area') . '</th>
                    <th class="text-center" rowspan="2">SUB LOCATION</th>
                    <th class="text-center sortable-header ' . ($sortColumn == 'model_no' ? 'active' : '') . '" rowspan="2" data-sort="model_no" style="cursor: pointer;">MODEL NO' . $getSortIcon('model_no') . '</th>
                    <th class="text-center sortable-header ' . ($sortColumn == 'equipment_name' ? 'active' : '') . '" rowspan="2" data-sort="equipment_name" style="cursor: pointer;">EQUIPMENT NAME' . $getSortIcon('equipment_name') . '</th>
                    <th class="text-center" rowspan="2">SL NO</th>
                    <th class="text-center sortable-header ' . ($sortColumn == 'unit_no' ? 'active' : '') . '" rowspan="2" data-sort="unit_no" style="cursor: pointer;">UNIT NO' . $getSortIcon('unit_no') . '</th>
                    <th class="text-center" rowspan="2">EQUIPMENT NO</th>
                    <th class="text-center" rowspan="2">EQUIPMENT TYPE</th>
                    <th class="text-center sortable-header ' . ($sortColumn == 'voltage' ? 'active' : '') . '" rowspan="2" data-sort="voltage" style="cursor: pointer;">VOLTAGE' . $getSortIcon('voltage') . '</th>';

            for ($i = 1; $i <= $maxCompCnt; $i++) {
                $thead .= '<th class="text-center" colspan="7">COMP #'.$i.'</th>';
            }
            $thead .= '<th class="text-center" colspan="4">CHILLER WATER</th>
                    <th class="text-center" colspan="4">CONDENSER WATER</th>';
            for ($i = 1; $i <= $maxCompCntA; $i++) {
                $thead .= '<th class="text-center" colspan="2">CHILLER WATER PUMP #'.$i.'</th>';
            }

            for ($i = 1; $i <= $maxCompCntB; $i++) {
                $thead .= '<th class="text-center" colspan="2">CONDENSER WATER PUMP #'.$i.'</th>';
            }

            for ($i = 1; $i <= $maxCompCntC; $i++) {
                $thead .= '<th class="text-center" colspan="2">COOLING TOWER #'.$i.'</th>';
            }

            $thead .= '<th class="text-center" rowspan="2">Operator Name</th></tr><tr>';

            for ($i = 1; $i <= $maxCompCnt; $i++) {
                $thead .= '<th class="text-center">SUCTION PR. PSIG</th>
                        <th class="text-center">DISC.PR. PSIG</th>
                        <th class="text-center">DISC TEM./°C</th>
                        <th class="text-center">DSH</th>
                        <th class="text-center">OIL LEVEL %</th>
                        <th class="text-center">COMP. LOAD %</th>
                        <th class="text-center">COMP. AMP</th>';
            }
            
            $chw_thead = '';
            for ($i = 0; $i < $maxCompCntA; $i++) {
                $chw_thead .= '<th class="text-center">NUMB</th><th class="text-center">AMP</th>';
            }
            
            $cdw_thead = '';
            for ($i = 0; $i < $maxCompCntB; $i++) {
                $cdw_thead .= '<th class="text-center">NUMB</th><th class="text-center">AMP</th>';
            }
            
            $ct_thead = '';
            for ($i = 0; $i < $maxCompCntC; $i++) {
                $ct_thead .= '<th class="text-center">NUMB</th><th class="text-center">AMP</th>';
            }
            $thead .= '<th class="text-center">INLET TEMP</th>
                    <th class="text-center">OUTLET TEMP</th>
                    <th class="text-center">INLET PR</th>
                    <th class="text-center">OUTLET PR</th>
                    <th class="text-center">INLET TEMP</th>
                    <th class="text-center">OUTLET TEMP</th>
                    <th class="text-center">INLET PR</th>
                    <th class="text-center">OUTLET PR</th>
                    '.$chw_thead.$cdw_thead.$ct_thead.'
                    </tr></thead>';

            // Build rows
            foreach ($dataRows as $dr) {
                $rowsHtml .= "<tr>
                    <td class='text-center'>{$dr['date']}</td>
                    <td class='text-center'>{$dr['time']}</td>
                    <td class='text-center'>{$dr['department_location']}</td>
                    <td class='text-center'>{$dr['department_dept']}</td>
                    <td class='text-center'>{$dr['area']}</td>
                    <td class='text-center'>{$dr['sub_location']}</td>
                    <td class='text-center'>{$dr['model_no']}</td>
                    <td class='text-left'>{$dr['equipment_name']}</td>
                    <td class='text-center'>{$dr['slno']}</td>
                    <td class='text-center'>{$dr['unit']}</td>
                    <td class='text-center'>{$dr['equipment_no']}</td>
                    <td class='text-center'>{$dr['equipment_type']}</td>
                    <td class='text-right'>{$dr['voltage']}</td>";

                for ($i = 0; $i < $maxCompCnt; $i++) {
                    if (isset($dr['comps'][$i])) {
                        $j = 0;
                        foreach ($dr['comps'][$i] as $val) {
                            $percent_sign = '';
                            if ($j == 4 || $j == 5) {
                                $percent_sign = '%';
                            }
                            $rowsHtml .= "<td class='text-right'>{$val}{$percent_sign}</td>";
                            $j++;
                        }
                    } else {
                        $rowsHtml .= '<td class="text-right">0</td><td class="text-right">0</td><td class="text-right">0</td><td class="text-right">0</td><td class="text-right">0</td><td class="text-right">0</td><td class="text-right">0</td>';
                    }
                }

                foreach ($dr['chw'] as $val) {
                    $rowsHtml .= "<td class='text-right'>{$val}</td>";
                }
                foreach ($dr['cow'] as $val) {
                    $rowsHtml .= "<td class='text-right'>{$val}</td>";
                }
                

                for ($i = 0; $i < $maxCompCntA; $i++) {
                    if (isset($dr['cd_pumps'][$i])) {
                        $j = 0;
                        foreach ($dr['cd_pumps'][$i] as $val) {
                            $rowsHtml .= "<td class='text-right'>{$val}</td>";
                            $j++;
                        }
                    } else {
                        $rowsHtml .= "<td class='text-right'></td><td class='text-right'>0</td>";
                    }
                }

                for ($i = 0; $i < $maxCompCntB; $i++) {
                    if (isset($dr['ch_pumps'][$i])) {
                        $j = 0;
                        foreach ($dr['ch_pumps'][$i] as $val) {
                            $rowsHtml .= "<td class='text-right'>{$val}</td>";
                            $j++;
                        }
                    } else {
                        $rowsHtml .= "<td class='text-right'></td><td class='text-right'>0</td>";
                    }
                }

                for ($i = 0; $i < $maxCompCntC; $i++) {
                    if (isset($dr['ctowers'][$i])) {
                        $j = 0;
                        foreach ($dr['ctowers'][$i] as $val) {
                            $rowsHtml .= "<td class='text-right'>{$val}</td>";
                            $j++;
                        }
                    } else {
                        $rowsHtml .= "<td class='text-right'></td><td class='text-right'>0</td>";
                    }
                }
                $rowsHtml .= "<td class='text-right'>{$dr['creator']}</td>";

                $rowsHtml .= "</tr>";
            }

            // Build pagination HTML
            $paginationHtml = $this->buildPagination($page, $totalPages, $totalRecords, $perPage);
            
            $html = '<div class="table-responsive"><table id="equipment-logs-table" class="table table-bordered table-striped">'.$thead.'<tbody>'.$rowsHtml.'</tbody></table></div>';
            $html .= $paginationHtml;

            return response()->json([
                'success' => true,
                'html' => $html,
                'pagination' => [
                    'current_page' => $page,
                    'total_pages' => $totalPages,
                    'total_records' => $totalRecords,
                    'per_page' => $perPage
                ],
                'sorting' => [
                    'column' => $sortColumn,
                    'direction' => $sortDirection
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching logs: ' . $e->getMessage()
            ], 500);
        }
    }

    // Build pagination HTML
    private function buildPagination($currentPage, $totalPages, $totalRecords, $perPage)
    {
        if ($totalPages <= 1) {
            return '<div class="row mt-3">
                        <div class="col-md-6">
                            <p class="text-muted">Showing ' . $totalRecords . ' entries</p>
                        </div>
                    </div>';
        }

        $html = '<div class="row mt-3">
                    <div class="col-md-6">
                        <p class="text-muted">Showing ' . (($currentPage - 1) * $perPage + 1) . ' to ' . min($currentPage * $perPage, $totalRecords) . ' of ' . $totalRecords . ' entries</p>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-end">';

        // Previous button
        if ($currentPage > 1) {
            $html .= '<li class="page-item"><a class="page-link pagination-link" href="#" data-page="' . ($currentPage - 1) . '">Previous</a></li>';
        } else {
            $html .= '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
        }

        // Page numbers
        $startPage = max(1, $currentPage - 2);
        $endPage = min($totalPages, $currentPage + 2);

        if ($startPage > 1) {
            $html .= '<li class="page-item"><a class="page-link pagination-link" href="#" data-page="1">1</a></li>';
            if ($startPage > 2) {
                $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }

        for ($i = $startPage; $i <= $endPage; $i++) {
            if ($i == $currentPage) {
                $html .= '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
            } else {
                $html .= '<li class="page-item"><a class="page-link pagination-link" href="#" data-page="' . $i . '">' . $i . '</a></li>';
            }
        }

        if ($endPage < $totalPages) {
            if ($endPage < $totalPages - 1) {
                $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
            $html .= '<li class="page-item"><a class="page-link pagination-link" href="#" data-page="' . $totalPages . '">' . $totalPages . '</a></li>';
        }

        // Next button
        if ($currentPage < $totalPages) {
            $html .= '<li class="page-item"><a class="page-link pagination-link" href="#" data-page="' . ($currentPage + 1) . '">Next</a></li>';
        } else {
            $html .= '<li class="page-item disabled"><span class="page-link">Next</span></li>';
        }

        $html .= '</ul></nav></div></div>';

        return $html;
    } 
}
