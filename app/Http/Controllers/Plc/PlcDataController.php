<?php

namespace App\Http\Controllers\Plc;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use App\Models\PlcAlert;
use App\Models\PlcData;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PlcDataController extends Controller
{
    public function getData($machine_id)
    {
        $data = PlcData::with('machine')
            ->where('machine_id', $machine_id)
            ->latest()
            ->first();

        return response()->json($data);
    }

    public function monitor($machine_id, Request $request)
    {
        // Lấy machine info
        $machine = Machine::findOrFail($machine_id);

        // Xử lý filter
        $isRealtime = $request->input('mode', 'realtime') === 'realtime';

        // Lấy thời gian hiện tại theo timezone của app
        $now = Carbon::now();

        // Nếu có filter date/time thì dùng, không thì dùng thời gian hiện tại
        $filterDate = $request->input('date') ? $request->input('date') : $now->format('Y-m-d');
        $filterTime = $request->input('time') ? $request->input('time') : $now->format('H:i');

        // Tạo datetime cho cả 2 chế độ
        $datetime = $isRealtime
            ? $now
            : Carbon::createFromFormat('Y-m-d H:i', $filterDate . ' ' . $filterTime);

        // Query base
        $query = PlcData::where('machine_id', $machine_id);

        // Lấy dữ liệu PLC
        if ($isRealtime) {
            $data = $query->latest()->first();
        } else {
            $data = $query->where('created_at', '<=', $datetime)
                        ->orderBy('created_at', 'desc')
                        ->first();
        }

        // Lấy các cảnh báo chưa được xử lý
        $alerts = PlcAlert::where('machine_id', $machine_id)
                        ->where('status', 'new')
                        ->when(!$isRealtime, function($query) use ($datetime) {
                            return $query->where('created_at', '<=', $datetime);
                        })
                        ->latest()
                        ->get()
                        ->groupBy('plc_data_key');

        // Thêm thông tin last_update để hiển thị thời gian dữ liệu
        $lastUpdate = $data ? Carbon::parse($data->created_at)->format('d/m/Y H:i:s') : null;

        return view('plc.plc.monitor', compact(
            'machine',
            'data',
            'alerts',
            'isRealtime',
            'filterDate',
            'filterTime',
            'lastUpdate'
        ));
    }

}
