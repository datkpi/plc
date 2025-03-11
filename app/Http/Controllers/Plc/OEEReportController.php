<?php

namespace App\Http\Controllers\Plc;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use App\Models\ProductionEntry;
use App\Services\OEECalculationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OEEExport;

class OEEReportController extends Controller
{
    protected $oeeService;

    public function __construct(OEECalculationService $oeeService)
    {
        $this->oeeService = $oeeService;
    }

    /**
     * Báo cáo OEE tổng hợp
     */
    public function index(Request $request)
    {
        $machines = Machine::where('status', true)->get();

        // Filters
        $machineId = $request->input('machine_id');
        $fromDate = $request->input('from_date', Carbon::now()->subDays(7)->format('Y-m-d'));
        $toDate = $request->input('to_date', Carbon::now()->format('Y-m-d'));

        $entries = ProductionEntry::with(['machine'])
            ->when($machineId, function($query) use ($machineId) {
                return $query->where('machine_id', $machineId);
            })
            ->when($fromDate, function($query) use ($fromDate) {
                return $query->whereDate('date', '>=', $fromDate);
            })
            ->when($toDate, function($query) use ($toDate) {
                return $query->whereDate('date', '<=', $toDate);
            })
            ->orderBy('date', 'desc')
            ->orderBy('shift')
            ->paginate(20);

        // Tính OEE cho mỗi entry
        $oeeData = [];
        foreach($entries as $entry) {
            $oeeData[$entry->id] = $this->oeeService->calculateShiftOEE(
                $entry->machine_id,
                $entry->date,
                $entry->shift
            );
        }

        return view('plc.reports.oee.index', compact('machines', 'entries', 'oeeData'));
    }

    /**
     * Báo cáo OEE theo ngày
     */
    public function daily(Request $request)
    {
        $machines = Machine::where('status', true)->get();
        $fromDate = $request->input('from_date', now()->subDays(7)->toDateString());
        $toDate = $request->input('to_date', now()->toDateString());
        $machineId = $request->input('machine_id');
    
        $dailyOEE = null;
        $summary = null;
        $selectedMachine = null;
    
        if ($machineId) {
            $selectedMachine = Machine::findOrFail($machineId);
            
            // Lấy dữ liệu OEE theo khoảng ngày
            $startDate = Carbon::parse($fromDate);
            $endDate = Carbon::parse($toDate);
            $dailyResults = [];
            $totalStats = [
                'availability' => 0,
                'performance' => 0,
                'quality' => 0,
                'oee' => 0,
                'valid_days' => 0
            ];
    
            // Lặp qua từng ngày trong khoảng
            for($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $result = $this->oeeService->calculateDailyOEE($machineId, $date->format('Y-m-d'));
                
                // Chỉ lấy ngày có dữ liệu
                if ($result['daily']['oee'] > 0) {
                    $dailyResults[$date->format('Y-m-d')] = $result;
                    $totalStats['availability'] += $result['daily']['availability'];
                    $totalStats['performance'] += $result['daily']['performance'];
                    $totalStats['quality'] += $result['daily']['quality'];
                    $totalStats['oee'] += $result['daily']['oee'];
                    $totalStats['valid_days']++;
                }
            }
    
            // Tính trung bình
            if ($totalStats['valid_days'] > 0) {
                $summary = [
                    'availability' => $totalStats['availability'] / $totalStats['valid_days'],
                    'performance' => $totalStats['performance'] / $totalStats['valid_days'],
                    'quality' => $totalStats['quality'] / $totalStats['valid_days'],
                    'oee' => $totalStats['oee'] / $totalStats['valid_days']
                ];
            }
    
            $dailyOEE = $dailyResults;
        }
    
        return view('plc.reports.oee.daily', compact(
            'machines',
            'fromDate',
            'toDate',
            'machineId',
            'dailyOEE',
            'summary',
            'selectedMachine'
        ));
    }

    /**
     * Báo cáo OEE theo tháng
     */
     public function monthly(Request $request)
    {
        $machines = Machine::where('status', true)->get();
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);
        $machineId = $request->input('machine_id');
        $method = $request->input('method', 'product'); // product or design

        $monthlyOEE = null;
        $monthlyOEEByDesign = null;
        $selectedMachine = null;

        if ($machineId) {
            $selectedMachine = Machine::findOrFail($machineId);

            if ($method == 'product') {
                $monthlyOEE = $this->oeeService->calculateMonthlyOEE($machineId, $year, $month);
            } else {
                // Lấy số ngày trong tháng
                $daysInMonth = Carbon::create($year, $month)->daysInMonth;

                // Giá trị mặc định
                $defaultMonthTime = $daysInMonth * 24 * 60; // Số ngày * 24 giờ * 60 phút
                $machine = Machine::find($machineId);
                $defaultDesignCapacity = $machine ? $machine->design_capacity : 0;

                // Lấy tham số từ form
                $params = [
                    'total_month_time' => $request->input('total_month_time', $defaultMonthTime),
                    'unplanned_time' => $request->input('unplanned_time', 0),
                    'design_capacity' => $request->input('design_capacity', $defaultDesignCapacity)
                ];

                $monthlyOEEByDesign = $this->oeeService->calculateMonthlyOEEByDesign(
                    $machineId,
                    $year,
                    $month,
                    $params
                );
            }
        }

        return view('plc.reports.oee.monthly', compact(
            'machines',
            'year',
            'month',
            'machineId',
            'method',
            'monthlyOEE',
            'monthlyOEEByDesign',
            'selectedMachine'
        ));
    }

    /**
     * API để lấy dữ liệu OEE tháng
     */
    public function getMonthlyOEE(Request $request)
    {
        try {
            $machineId = $request->machine_id;
            $year = $request->year;
            $month = $request->month;
            $method = $request->method; // 'product' hoặc 'design'

            if (!$machineId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng chọn máy'
                ], 400);
            }

            $machine = Machine::findOrFail($machineId);

            if ($method === 'design') {
                // Validate form inputs
                $request->validate([
                    'total_month_time' => 'required|numeric|min:0',
                    'unplanned_time' => 'required|numeric|min:0',
                    'design_capacity' => 'required|numeric|min:0'
                ]);

                $result = $this->oeeService->calculateMonthlyOEEByDesign(
                    $machineId,
                    $year,
                    $month,
                    $request->only(['total_month_time', 'unplanned_time', 'design_capacity'])
                );
            } else {
                $result = $this->oeeService->calculateMonthlyOEE($machineId, $year, $month);
            }

            return response()->json([
                'success' => true,
                'data' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xuất báo cáo Excel
     */
    public function export(Request $request)
    {
        $type = $request->input('type', 'daily');
        $machineId = $request->input('machine_id');
        $filename = 'oee_report_' . Carbon::now()->format('Ymd_His') . '.xlsx';

        if ($type == 'daily') {
            $date = $request->input('date');
            return Excel::download(new OEEExport($machineId, $date, 'daily'), $filename);
        } elseif ($type == 'monthly') {
            $year = $request->input('year');
            $month = $request->input('month');
            $method = $request->input('method', 'product');
            return Excel::download(new OEEExport($machineId, "$year-$month", 'monthly', $method), $filename);
        } else {
            $fromDate = $request->input('from_date');
            $toDate = $request->input('to_date');
            return Excel::download(new OEEExport($machineId, "$fromDate:$toDate", 'index'), $filename);
        }
    }

    /**
     * API để lấy dữ liệu OEE
     */
    public function show(Request $request)
    {
        try {
            $machineId = $request->machine_id;

            if (!$machineId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng chọn máy'
                ], 400);
            }

            $method = $request->method; // 'product' hoặc 'design'
            $reportType = $request->report_type; // 'daily' hoặc 'monthly'

            $machine = Machine::findOrFail($machineId);

            if ($reportType === 'monthly') {
                $year = $request->year;
                $month = $request->month;

                if ($method === 'design') {
                    // Validate form inputs
                    $request->validate([
                        'total_month_time' => 'required|numeric|min:0',
                        'unplanned_time' => 'required|numeric|min:0',
                        'design_capacity' => 'required|numeric|min:0'
                    ]);

                    $result = $this->oeeService->calculateMonthlyOEEByDesign(
                        $machineId,
                        $year,
                        $month,
                        $request->only(['total_month_time', 'unplanned_time', 'design_capacity'])
                    );
                } else {
                    $result = $this->oeeService->calculateMonthlyOEE($machineId, $year, $month);
                }
            } else {
                $date = $request->date;
                $result = $this->oeeService->calculateDailyOEE($machineId, $date);
            }

            return response()->json([
                'success' => true,
                'data' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
