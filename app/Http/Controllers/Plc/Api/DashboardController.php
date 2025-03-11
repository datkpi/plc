<?php
namespace App\Http\Controllers\Plc\Api;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use App\Models\ProductionEntry;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getStats()
    {
        return response()->json([
            'machine_count' => Machine::where('status', true)->count(),
            'today_production' => ProductionEntry::whereDate('date', today())
                                    ->sum('output_quantity'),
            'today_defect' => ProductionEntry::whereDate('date', today())
                                ->sum('defect_weight'),
            'oee' => $this->calculateTodayOEE()
        ]);
    }

    public function getMachineStatus()
    {
        $machines = Machine::with(['plcData' => function($query) {
            $query->latest();
        }])->get()->map(function($machine) {
            return [
                'id' => $machine->id,
                'name' => $machine->name,
                'status' => $machine->plcData?->status ?? false,
                'product_code' => $machine->plcData?->product_code ?? '-',
                'speed' => $machine->plcData?->speed ?? 0
            ];
        });

        return response()->json($machines);
    }

    public function getTopProducts()
    {
        $products = ProductionEntry::with('product')
            ->whereBetween('date', [now()->startOfWeek(), now()])
            ->selectRaw('product_code, sum(output_quantity) as quantity')
            ->groupBy('product_code')
            ->orderByDesc('quantity')
            ->limit(5)
            ->get()
            ->map(function($entry) {
                return [
                    'name' => $entry->product->name,
                    'quantity' => $entry->quantity
                ];
            });

        return response()->json($products);
    }

    public function getProductionTrend()
    {
        $trend = ProductionEntry::whereBetween('date', [
                now()->subDays(6), now()
            ])
            ->selectRaw('date, sum(output_quantity) as quantity')
            ->groupBy('date')
            ->get()
            ->map(function($entry) {
                return [
                    'date' => $entry->date->format('Y-m-d'),
                    'quantity' => $entry->quantity
                ];
            });

        return response()->json($trend);
    }

    public function getOEE()
    {
        $machines = Machine::where('status', true)->get();
        $data = [];

        foreach($machines as $machine) {
            $oee = $this->calculateMachineOEE($machine->id);
            $data[] = [
                'machine' => $machine->name,
                'availability' => $oee['availability'] * 100,
                'performance' => $oee['performance'] * 100,
                'quality' => $oee['quality'] * 100,
                'oee' => $oee['oee'] * 100
            ];
        }

        return response()->json($data);
    }

    protected function calculateTodayOEE()
    {
        $oeeService = new OEECalculationService();
        $todayOEE = $oeeService->calculateDailyOEE(null, today());
        return $todayOEE['daily']['oee'] * 100;
    }

    protected function calculateMachineOEE($machineId)
    {
        $oeeService = new OEECalculationService();
        return $oeeService->calculateDailyOEE($machineId, today())['daily'];
    }
}

// app/Http/Controllers/Plc/Api/ProductionController.php
class ProductionController extends Controller
{
    public function getProductionData(Request $request)
    {
        $query = ProductionEntry::with(['machine', 'product']);

        // Filter
        if ($machineId = $request->get('machine_id')) {
            $query->where('machine_id', $machineId);
        }
        if ($fromDate = $request->get('from_date')) {
            $query->whereDate('date', '>=', $fromDate);
        }
        if ($toDate = $request->get('to_date')) {
            $query->whereDate('date', '<=', $toDate);
        }

        // Sort
        if ($sort = $request->get('sort')) {
            $query->orderBy($sort, $request->get('desc') ? 'desc' : 'asc');
        }

        // Paginate
        $perPage = $request->get('take', 20);
        $page = $request->get('skip', 0) / $perPage + 1;

        $entries = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $entries->items(),
            'totalCount' => $entries->total()
        ]);
    }
}

// app/Http/Controllers/Plc/Api/MachineController.php
class MachineController extends Controller
{
    public function getMachineData(Request $request)
    {
        $query = Machine::query();

        // Filter
        if ($search = $request->get('filter')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status === 'true');
        }

        // Sort
        if ($sort = $request->get('sort')) {
            $query->orderBy($sort, $request->get('desc') ? 'desc' : 'asc');
        }

        // Paginate
        $perPage = $request->get('take', 20);
        $page = $request->get('skip', 0) / $perPage + 1;

        $machines = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $machines->items(),
            'totalCount' => $machines->total()
        ]);
    }
}
