<?php

namespace App\Http\Controllers\Plc;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use App\Models\PlcData;
use App\Models\PlcAlert;
use App\Models\MachineThreshold;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function show($machineId)
    {
        try {
            $machine = Machine::findOrFail($machineId);

            // Lấy các threshold được hiển thị trên chart (mặc định)
            $thresholds = MachineThreshold::where('machine_id', $machineId)
                ->where('show_on_chart', true)
                ->where('status', true)
                ->get();

            // Tạo series config từ thresholds
            $series = $thresholds->map(function($threshold) {
                return [
                    'name' => $threshold->plc_data_key,
                    'valueField' => $threshold->plc_data_key,
                    'color' => $threshold->color ?? '#666666',
                    'isDefault' => true
                ];
            });

            // Lấy tất cả các tag có thể chọn từ PlcData
            $availableTags = PlcData::getAlertableColumns();

            return view('plc.chart.show', compact('machine', 'series', 'machineId', 'availableTags'));

        } catch (\Exception $e) {
            \Log::error('Chart show error:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function getData(Request $request, $machineId)
    {
        try {
            $isRealtime = $request->boolean('realtime', true);
            $product = $request->input('product');
            $lastTimestamp = $request->input('last_timestamp');

            // Query base
            $query = PlcData::where('machine_id', $machineId);

            // Xử lý thời gian
            if ($isRealtime) {
                if ($lastTimestamp) {
                    $query->where('created_at', '>', $lastTimestamp);
                } else {
                    $thirtyMinutesAgo = Carbon::now()->subMinutes(30);
                    $query->where('created_at', '>=', $thirtyMinutesAgo);
                }
            } else {
                $startTime = Carbon::parse($request->start_time);
                $endTime = Carbon::parse($request->end_time);
                $query->whereBetween('created_at', [$startTime, $endTime]);
            }

            // Filter theo sản phẩm
            if ($product) {
                $query->where('datalog_data_ma_sp', $product);
            }

            // Lấy tất cả các trường có thể hiển thị
            $availableTags = PlcData::getAlertableColumns();
            $selectFields = ['id', 'created_at as timestamp', 'datalog_data_ma_sp as product'];
            
            // Chỉ lấy các trường parameter, bỏ qua boolean
            foreach ($availableTags['parameter'] as $tag) {
                $selectFields[] = $tag['key'];
            }

            $data = $query->select($selectFields)
                ->orderBy('timestamp', 'asc')
                ->get();

            // Ghi log số lượng dữ liệu
            \Log::info("Số lượng dữ liệu cho máy $machineId: " . $data->count() . ", Realtime: " . ($isRealtime ? 'Yes' : 'No'));
            \Log::info("Các trường được chọn: " . implode(', ', $selectFields));

            // Lấy cảnh báo theo khoảng thời gian tương ứng
            $alertsQuery = PlcAlert::where('machine_id', $machineId)
                ->where('status', 'new');

            if ($isRealtime) {
                if ($lastTimestamp) {
                    $alertsQuery->where('created_at', '>', $lastTimestamp);
                } else {
                    $alertsQuery->where('created_at', '>=', Carbon::now()->subMinutes(30));
                }
            } else {
                $alertsQuery->whereBetween('created_at', [$startTime, $endTime]);
            }

            $alerts = $alertsQuery->get()->groupBy('plc_data_id');

            // Ghi log số lượng cảnh báo
            \Log::info("Số lượng nhóm cảnh báo cho máy $machineId: " . $alerts->count());

            // Map dữ liệu với cảnh báo
            $mappedData = $data->map(function($item) use ($alerts) {
                $point = $item->toArray();
                $point['timestamp'] = Carbon::parse($item->timestamp)->format('Y-m-d H:i:s');
                $point['hasWarning'] = false;
                $point['warnings'] = [];

                if (isset($alerts[$item->id])) {
                    $point['hasWarning'] = true;
                    foreach($alerts[$item->id] as $alert) {
                        $point['warnings'][] = [
                            'key' => $alert->plc_data_key,
                            'value' => $alert->value,
                            'message' => $alert->message,
                            'type' => $alert->type,
                            'min_value' => $alert->min_value,
                            'max_value' => $alert->max_value
                        ];
                    }
                }

                return $point;
            });

            // Thêm thông tin time range vào response
            $timeRange = [
                'start' => $isRealtime
                    ? ($lastTimestamp ? $lastTimestamp : Carbon::now()->subMinutes(30)->format('Y-m-d H:i:s'))
                    : $startTime->format('Y-m-d H:i:s'),
                'end' => $isRealtime
                    ? Carbon::now()->format('Y-m-d H:i:s')
                    : $endTime->format('Y-m-d H:i:s')
            ];

            return response()->json([
                'success' => true,
                'data' => $mappedData,
                'timeRange' => $timeRange
            ]);

        } catch (\Exception $e) {
            \Log::error('Chart data error:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getProducts($machineId)
    {
        try {
            $products = PlcData::where('machine_id', $machineId)
                ->select('datalog_data_ma_sp')
                ->distinct()
                ->whereNotNull('datalog_data_ma_sp')
                ->orderBy('datalog_data_ma_sp')
                ->pluck('datalog_data_ma_sp');

            return response()->json([
                'success' => true,
                'products' => $products
            ]);

        } catch (\Exception $e) {
            \Log::error('Get products error:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
