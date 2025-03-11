<?php
namespace App\Http\Controllers\Plc;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use App\Models\Product;
use App\Models\ProductionEntry;
use Illuminate\Http\Request;

class ProductionEntryController extends Controller
{
    public function index()
    {
        $entries = ProductionEntry::with(['machine', 'product'])
            ->latest()
            ->paginate(20);

        return view('plc.production.entries.index', compact('entries'));
    }

    public function create()
    {
        $machines = Machine::where('status', true)->get();
        $products = Product::all();

        return view('plc.production.entries.create', compact('machines', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'machine_id' => 'required|exists:machine,id',
            'date' => 'required|date',
            'shift' => 'required',
            'product_code' => 'required|exists:products,code',
            'output_quantity' => 'required|integer|min:0',
            'good_quantity' => 'required|integer|min:0|lte:output_quantity',
            'defect_weight' => 'required|numeric|min:0',
            'waste_weight' => 'required|numeric|min:0',
            'operator_team' => 'required|string',
            'operator_name' => 'required|string',
            'machine_operator' => 'required|string',
            'quality_checker' => 'required|string',
            'warehouse_staff' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        ProductionEntry::create($validated);

        return redirect()
            ->route('plc.production.entries.index')
            ->with('success', 'Thêm dữ liệu sản xuất thành công');
    }

    public function edit($id)
    {
        $entry = ProductionEntry::findOrFail($id);
        $machines = Machine::where('status', true)->get();
        $products = Product::all();

        return view('plc.production.entries.edit', compact('entry', 'machines', 'products'));
    }

    public function update(Request $request, $id)
    {
        $entry = ProductionEntry::findOrFail($id);

        $validated = $request->validate([
            'machine_id' => 'required|exists:machine,id',
            'date' => 'required|date',
            'shift' => 'required',
            'product_code' => 'required|exists:products,code',
            'output_quantity' => 'required|integer|min:0',
            'good_quantity' => 'required|integer|min:0|lte:output_quantity',
            'defect_weight' => 'required|numeric|min:0',
            'waste_weight' => 'required|numeric|min:0',
            'operator_team' => 'required|string',
            'operator_name' => 'required|string',
            'machine_operator' => 'required|string',
            'quality_checker' => 'required|string',
            'warehouse_staff' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        $entry->update($validated);

        return redirect()
            ->route('plc.production.entries.index')
            ->with('success', 'Cập nhật dữ liệu sản xuất thành công');
    }

    public function destroy($id)
    {
        $entry = ProductionEntry::findOrFail($id);
        $entry->delete();

        return redirect()
            ->route('plc.production.entries.index')
            ->with('success', 'Xóa dữ liệu sản xuất thành công');
    }
}
