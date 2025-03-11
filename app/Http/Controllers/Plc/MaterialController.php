<?php
namespace App\Http\Controllers\Plc;

use App\Models\Material;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = Material::query();

        // Search
        if ($search = $request->search) {
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($type = $request->type) {
            $query->where('type', $type);
        }

        $materials = $query->orderBy('code')
                         ->paginate(20)
                         ->withQueryString();

        return view('plc.materials.index', compact('materials'));
    }

    public function create()
    {
        return view('plc.materials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:materials,code',
            'name' => 'required',
            'type' => 'required|in:PE80,PE100,PPR'
        ]);

        Material::create($validated);

        return redirect()
            ->route('plc.materials.index')
            ->with('success', 'Thêm nguyên liệu thành công');
    }

    public function edit($id)
    {
        $material = Material::findOrFail($id);
        return view('plc.materials.edit', compact('material'));
    }

    public function update(Request $request, $id)
    {
        $material = Material::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|unique:materials,code,'.$id,
            'name' => 'required',
            'type' => 'required|in:PE80,PE100,PPR'
        ]);

        $material->update($validated);

        return redirect()
            ->route('plc.materials.index')
            ->with('success', 'Cập nhật nguyên liệu thành công');
    }

    public function destroy($id)
    {
        $material = Material::findOrFail($id);
        $material->delete();

        return redirect()
            ->route('plc.materials.index')
            ->with('success', 'Xóa nguyên liệu thành công');
    }
}
