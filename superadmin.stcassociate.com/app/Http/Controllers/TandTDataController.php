<?php

namespace App\Http\Controllers;

use App\ToolstackelsData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TandTDataController extends Controller
{
    public function show()
    {
        ToolstackelsData::ensureColumns();
        return view('pages.tandtdata', ['page_title' => 'T&T Data']);
    }

    public function list(Request $request)
    {
        ToolstackelsData::ensureColumns();
        $draw = $request->get('draw', 1);
        $start = (int) $request->get('start', 0);
        $rowperpage = (int) ($request->get('length', 10) ?: 10);
        $order = $request->get('order') ?? [];
        $columns = $request->get('columns') ?? [];
        $searchValue = $request->get('search')['value'] ?? '';

        $columnIndex = $order[0]['column'] ?? 0;
        $columnName = $columns[$columnIndex]['data'] ?? 'id';
        $columnSortOrder = $order[0]['dir'] ?? 'desc';
        $sortMap = [
            'title' => 'title',
            'department_name' => 'department_name',
            'model_no' => 'model_no',
            'serial_thm_no' => 'serial_thm_no',
            'make_name' => 'make_name',
            'calibration_date' => 'calibration_date',
            'calibration_due' => 'calibration_due',
            'id' => 'id',
        ];
        $orderColumn = $sortMap[$columnName] ?? 'id';

        $query = ToolstackelsData::query()->where('status', '1');
        $searchQuery = function ($q) use ($searchValue) {
            $q->where('title', 'like', '%'.$searchValue.'%')
                ->orWhere('department_name', 'like', '%'.$searchValue.'%')
                ->orWhere('model_no', 'like', '%'.$searchValue.'%')
                ->orWhere('serial_thm_no', 'like', '%'.$searchValue.'%')
                ->orWhere('make_name', 'like', '%'.$searchValue.'%')
                ->orWhere('range_value', 'like', '%'.$searchValue.'%');
        };

        $totalRecords = ToolstackelsData::where('status', '1')->count();
        $totalFilter = (clone $query)->when($searchValue !== '', $searchQuery)->count();
        $records = $query->when($searchValue !== '', $searchQuery)
            ->orderBy($orderColumn, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data = [];
        foreach ($records as $row) {
            $left = $this->leftStatus($row->calibration_due);
            $docs = trim((string) $row->docs);
            $docUrl = $this->docUrl($docs);
            $cert = $docUrl
                ? '<a href="'.e($docUrl).'" target="_blank" class="btn btn-info btn-sm" title="View Certificate"><i class="fas fa-eye"></i></a>'
                : 'N/A';
            $statusHtml = $left['cal_status'] === 'Expired'
                ? '<span class="badge badge-danger">Expired</span>'
                : ($left['cal_status'] === 'On Time' ? '<span class="badge badge-success">On Time</span>' : '');
            $id = (int) $row->id;
            $data[] = [
                'id' => $id,
                'department_name' => $row->department_name ?? '',
                'title' => $row->title ?? '',
                'model_no' => $row->model_no ?? '',
                'serial_thm_no' => $row->serial_thm_no ?? '',
                'make_name' => $row->make_name ?? '',
                'range_value' => $row->range_value ?? '',
                'calibration_date' => $this->fmtDate($row->calibration_date),
                'calibration_due' => $this->fmtDate($row->calibration_due),
                'left_day' => $left['left_day'],
                'cal_status' => $statusHtml,
                'docs' => $cert,
                'actionData' => '
                    <a href="javascript:void(0)" class="btn btn-primary btn-sm edit-modal-btn" data-id="'.$id.'"><i class="fas fa-edit"></i></a>
                    <a href="javascript:void(0)" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete-modal" onclick=$("#delete_id").val("'.$id.'")><i class="fas fa-trash"></i></a>
                ',
            ];
        }

        return response()->json([
            'draw' => (int) $draw,
            'iTotalRecords' => $totalRecords,
            'iTotalDisplayRecords' => $totalFilter,
            'aaData' => $data,
        ]);
    }

    public function get(Request $request)
    {
        ToolstackelsData::ensureColumns();
        $row = ToolstackelsData::find($request->id);
        if (!$row) {
            return response()->json(['success' => false, 'message' => 'Record not found!']);
        }
        $row->calibration_date = $this->ymd($row->calibration_date);
        $row->calibration_due = $this->ymd($row->calibration_due);
        return response()->json(['success' => true, 'data' => $row]);
    }

    public function store(Request $request)
    {
        return $this->save($request, null);
    }

    public function update(Request $request)
    {
        return $this->save($request, $request->id);
    }

    public function delete(Request $request)
    {
        $row = ToolstackelsData::find($request->id);
        if (!$row) {
            return response()->json(['success' => false, 'message' => 'Record deletion failed!']);
        }
        $row->status = 0;
        $row->save();
        return response()->json(['success' => true, 'message' => 'Record deleted successfully!']);
    }

    private function save(Request $request, $id)
    {
        ToolstackelsData::ensureColumns();
        $issued = trim((string) $request->calibration_date);
        $due = trim((string) $request->calibration_due);
        if ($issued === '' || $due === '') {
            return response()->json(['success' => false, 'message' => 'Calibration dates are required.']);
        }
        if (strtotime($due) < strtotime($issued)) {
            return response()->json(['success' => false, 'message' => 'Calibration Due cannot be less than Issued Date.']);
        }

        $row = $id ? ToolstackelsData::find($id) : new ToolstackelsData();
        if ($id && !$row) {
            return response()->json(['success' => false, 'message' => 'Record not found!']);
        }

        $row->title = $request->title ?? '';
        $row->department_name = $request->department_name ?? '';
        $row->model_no = $request->model_no ?? '';
        $row->serial_thm_no = $request->serial_thm_no ?? '';
        $row->make_name = $request->make_name ?? '';
        $row->range_value = $request->range_value ?? '';
        $row->calibration_date = $issued;
        $row->calibration_due = $due;
        $row->status = 1;
        if (!$id) {
            $row->created_by = auth()->id() ?: 0;
            $row->docs = $row->docs ?? '';
        }

        if ($request->hasFile('certificate')) {
            $uploaded = $this->uploadCertificate($request->file('certificate'), (int) ($row->id ?? 0));
            if (empty($uploaded['ok'])) {
                return response()->json(['success' => false, 'message' => $uploaded['error'] ?? 'Certificate upload failed.']);
            }
            $row->docs = $uploaded['url'];
        }

        $row->save();
        return response()->json(['success' => true, 'message' => $id ? 'Record updated successfully!' : 'Record saved successfully!']);
    }

    private function uploadCertificate($file, int $id): array
    {
        $ext = strtolower($file->getClientOriginalExtension() ?: 'pdf');
        if (!in_array($ext, ['pdf', 'jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
            return ['ok' => false, 'error' => 'Certificate must be PDF or an image.'];
        }
        if ($ext === 'jpeg') {
            $ext = 'jpg';
        }
        $objectKey = 'tandtdata/'.($id > 0 ? $id : 'new').'_'.date('YmdHis').'_'.uniqid().'.'.$ext;
        try {
            $ok = Storage::disk('s3')->put($objectKey, file_get_contents($file->getRealPath()), 'private');
            if (!$ok) {
                return ['ok' => false, 'error' => 'Upload to Cloudflare failed.'];
            }
            $base = rtrim((string) (env('R2_PUBLIC_URL') ?: env('AWS_URL') ?: ''), '/');
            if ($base === '') {
                return ['ok' => false, 'error' => 'Set R2_PUBLIC_URL in .env.'];
            }
            return ['ok' => true, 'url' => $base.'/'.$objectKey];
        } catch (\Throwable $e) {
            report($e);
            return ['ok' => false, 'error' => 'Cloud error: '.$e->getMessage()];
        }
    }

    private function leftStatus($due): array
    {
        $ts = $due ? strtotime((string) $due) : false;
        if (!$ts) {
            return ['left_day' => '', 'cal_status' => ''];
        }
        $days = (int) round(($ts - strtotime(date('Y-m-d'))) / 86400);
        return [
            'left_day' => $days,
            'cal_status' => $days < 0 ? 'Expired' : 'On Time',
        ];
    }

    private function fmtDate($val): string
    {
        $ts = $val ? strtotime((string) $val) : false;
        return $ts ? date('d-m-Y', $ts) : '';
    }

    private function ymd($val): string
    {
        $ts = $val ? strtotime((string) $val) : false;
        return $ts ? date('Y-m-d', $ts) : '';
    }

    private function docUrl(string $docs): string
    {
        if ($docs === '') {
            return '';
        }
        if (stripos($docs, 'http') === 0) {
            return $docs;
        }
        $base = rtrim((string) env('STC_TANDT_DOC_URL', 'https://stcassociate.com/stc_agent47/docs'), '/');
        return $base.'/'.ltrim($docs, '/');
    }
}
