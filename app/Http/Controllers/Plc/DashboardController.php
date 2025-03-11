<?php
namespace App\Http\Controllers\Plc;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use App\Models\ProductionEntry;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Thông số tổng quan
        $stats = [
            'total_machines' => Machine::where('status', true)->count(),
            'total_products' => Product::count(),
            'total_production' => ProductionEntry::whereDate('date', today())->sum('output_quantity'),
            'total_defect' => ProductionEntry::whereDate('date', today())->sum('defect_weight'),
        ];

        // 2. OEE trung bình hôm nay
        $oeeService = new OEECalculationService();
        $todayOEE = $oeeService->calculateDailyOEE(null, today());

        // 3. Tình trạng máy hiện tại
        $machines = Machine::with(['plcData' => function($query) {
            $query->latest();
        }])->get();

        // 4. Top 5 sản phẩm có sản lượng cao nhất trong tuần
        $topProducts = ProductionEntry::with('product')
            ->whereBetween('date', [now()->startOfWeek(), now()])
            ->selectRaw('product_code, sum(output_quantity) as total_quantity')
            ->groupBy('product_code')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        // 5. Biểu đồ sản lượng 7 ngày gần nhất
        $dailyProduction = ProductionEntry::whereBetween('date', [
                now()->subDays(6), now()
            ])
            ->selectRaw('date, sum(output_quantity) as total_quantity')
            ->groupBy('date')
            ->get();

        return view('plc.dashboard.index', compact(
            'stats',
            'todayOEE',
            'machines',
            'topProducts',
            'dailyProduction'
        ));
    }
}
