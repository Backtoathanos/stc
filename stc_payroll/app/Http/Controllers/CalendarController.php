<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CalendarLeaveType;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        return view('pages.calendar', [
            'page_title' => 'Calendar - Working Days Management'
        ]);
    }

    public function getLeaves(Request $request)
    {
        $year = $request->input('year', date('Y'));
        
        $leaves = CalendarLeaveType::whereYear('date', $year)
            ->orderBy('date')
            ->get()
            ->groupBy(function($item) {
                return $item->date->format('Y-m-d');
            });
        
        $result = [];
        foreach ($leaves as $date => $leaveItems) {
            $result[$date] = $leaveItems->map(function($item) {
                return [
                    'id' => $item->id,
                    'leave_type' => $item->leave_type,
                    'description' => $item->description
                ];
            })->toArray();
        }
        
        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    public function getDateLeaves(Request $request, $date)
    {
        $leaves = CalendarLeaveType::where('date', $date)
            ->orderBy('leave_type')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $leaves->map(function($item) {
                return [
                    'id' => $item->id,
                    'leave_type' => $item->leave_type,
                    'description' => $item->description,
                    'created_at' => $item->created_at->format('Y-m-d H:i:s')
                ];
            })
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'leave_type' => 'required|string|max:50',
            'description' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();
            
            $leaveType = CalendarLeaveType::create([
                'date' => $request->date,
                'leave_type' => $request->leave_type,
                'description' => $request->description
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Leave type added successfully',
                'data' => [
                    'id' => $leaveType->id,
                    'leave_type' => $leaveType->leave_type,
                    'description' => $leaveType->description
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error adding leave type: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $leaveType = CalendarLeaveType::find($id);
        
        if (!$leaveType) {
            return response()->json([
                'success' => false,
                'message' => 'Leave type not found'
            ], 404);
        }

        try {
            DB::beginTransaction();
            
            $date = $leaveType->date->format('Y-m-d');
            $leaveType->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Leave type removed successfully',
                'date' => $date
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error removing leave type: ' . $e->getMessage()
            ], 500);
        }
    }
}

