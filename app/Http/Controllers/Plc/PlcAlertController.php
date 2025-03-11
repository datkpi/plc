<?php

namespace App\Http\Controllers\Plc;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use App\Models\PlcAlert;
use App\Models\PlcAlertSummary;
use App\Models\PlcData;
use Illuminate\Http\Request;

class PlcAlertController extends Controller
{
    public function index(Request $request)
    {
        // Get base query
        $query = PlcAlertSummary::with(['machine']);

        // Filter by machine
        if ($request->filled('machine_id')) {
            $query->where('machine_id', $request->machine_id);
        }

        // Filter by plc_data_key
        if ($request->filled('plc_data_key')) {
            $query->where('plc_data_key', $request->plc_data_key);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('first_occurred_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('last_occurred_at', '<=', $request->date_to);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by resolved_by
        if ($request->filled('resolved_by')) {
            $query->where('resolved_by', $request->resolved_by);
        }

        // Get data for filter dropdowns
        $machines = Machine::orderBy('name')->get();
        $plcDataKeys = PlcData::getAlertableColumns(); // Lấy danh sách các key có thể cảnh báo
        $resolvers = PlcAlertSummary::whereNotNull('resolved_by')
                        ->distinct()
                        ->pluck('resolved_by');

        // Get paginated results
        $summaries = $query->latest('last_occurred_at')->paginate(20);

        return view('plc.plc.alert', compact(
            'summaries',
            'machines',
            'plcDataKeys',
            'resolvers'
        ));
    }

    public function updateStatus($id)
    {
        // Lấy summary cần resolve
        $summary = PlcAlertSummary::findOrFail($id);

        // Cập nhật status của summary
        $summary->update([
            'status' => PlcAlertSummary::STATUS_RESOLVED,
            'resolved_by' => auth()->user()->name,
            'resolved_at' => now()
        ]);

        // Cập nhật tất cả PlcAlert liên quan đến summary này
        PlcAlert::where([
            'machine_id' => $summary->machine_id,
            'plc_data_key' => $summary->plc_data_key,
            'status' => 'new'
        ])->update([
            'status' => 'resolved',
            'resolved_by' => auth()->user()->name,
            'resolved_at' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'resolved_by' => auth()->user()->name,
            'resolved_at' => now()->format('d/m/Y H:i:s')
        ]);
    }

    public function history($summaryId)
    {
        // Xem lịch sử chi tiết của một summary
        $summary = PlcAlertSummary::with('machine')->findOrFail($summaryId);

        $alerts = PlcAlert::where([
            'machine_id' => $summary->machine_id,
            'plc_data_key' => $summary->plc_data_key
        ])
        ->latest()
        ->paginate(50);

        return view('plc.plc.alert-history', compact('summary', 'alerts'));
    }
}
