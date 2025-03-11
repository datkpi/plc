<?php

namespace App\Http\Controllers\Plc;

use App\Http\Controllers\Controller;
use App\Repositories\Plc\MachineRepository;
use Illuminate\Http\Request;

class MachineController extends Controller
{
    protected $repository;

    public function __construct(MachineRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $machines = $this->repository->paginate();
        return view('plc.machine.index', compact('machines'));
    }

    public function create()
    {
        return view('plc.machine.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->repository->validateCreate());
        $this->repository->create($data);
        return redirect()->route('plc.machine.index')
            ->with('success', 'Thêm máy thành công!');
    }

    public function edit($id)
    {
        $machine = $this->repository->find($id);
        return view('plc.machine.edit', compact('machine'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate($this->repository->validateUpdate($id));
        $this->repository->update($data, $id);
        return redirect()->route('plc.machine.index')
            ->with('success', 'Cập nhật máy thành công!');
    }

    public function destroy($id)
    {
        $machine = $this->repository->find($id);
        if($machine->plcData()->exists()) {
            return back()->with('error', 'Không thể xóa máy đã có dữ liệu!');
        }

        $this->repository->delete($id);
        return redirect()->route('plc.machine.index')
            ->with('success', 'Xóa máy thành công!');
    }

    public function toggleStatus($id)
    {
        $this->repository->toggleStatus($id);
        return back()->with('success', 'Cập nhật trạng thái thành công!');
    }
}
