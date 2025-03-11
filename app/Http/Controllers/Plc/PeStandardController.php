<?php

namespace App\Http\Controllers\Plc;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use App\Models\PeCoilStandard;
use App\Models\PlcAlert;
use App\Models\PlcData;
use Carbon\Carbon;
use Illuminate\Http\Request;

// app/Http/Controllers/Plc/PeStandardController.php
class PeStandardController extends Controller
{
    public function index(Request $request)
    {
        $query = PeCoilStandard::query();

        // Search by diameter
        if ($search = $request->search) {
            $query->where('diameter', 'like', "%{$search}%");
        }

        $standards = $query->orderBy('diameter')
                         ->paginate(20)
                         ->withQueryString();

        return view('plc.pe_standards.index', compact('standards'));
    }

    public function create()
    {
        return view('plc.pe_standards.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'diameter' => 'required|integer|min:1|unique:pe_coil_standards',
            'length' => 'required|integer|min:1'
        ]);

        PeCoilStandard::create($validated);

        return redirect()
            ->route('plc.pe_standards.index')
            ->with('success', 'Thêm tiêu chuẩn cuộn PE thành công');
    }

    public function edit($id)
    {
        $standard = PeCoilStandard::findOrFail($id);
        return view('plc.pe_standards.edit', compact('standard'));
    }

    public function update(Request $request, $id)
    {
        $standard = PeCoilStandard::findOrFail($id);

        $validated = $request->validate([
            'diameter' => 'required|integer|min:1|unique:pe_coil_standards,diameter,'.$id,
            'length' => 'required|integer|min:1'
        ]);

        $standard->update($validated);

        return redirect()
            ->route('plc.pe_standards.index')
            ->with('success', 'Cập nhật tiêu chuẩn cuộn PE thành công');
    }

    public function destroy($id)
    {
        $standard = PeCoilStandard::findOrFail($id);
        $standard->delete();

        return redirect()
            ->route('plc.pe_standards.index')
            ->with('success', 'Xóa tiêu chuẩn cuộn PE thành công');
    }
}
