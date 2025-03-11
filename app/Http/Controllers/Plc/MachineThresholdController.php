<?php

namespace App\Http\Controllers\Plc;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use App\Models\PlcData;
use App\Models\MachineThreshold;
use App\Repositories\Plc\MachineThresholdRepository;
use Illuminate\Http\Request;

class MachineThresholdController extends Controller
{
   protected $repository;

   public function __construct(MachineThresholdRepository $repository)
   {
       $this->repository = $repository;
   }

   /**
    * Hiển thị danh sách cảnh báo của máy
    */
   public function show($machine_id)
   {
       $machine = Machine::findOrFail($machine_id);
       $thresholds = $this->repository->getByMachine($machine_id);
       $availableColumns = PlcData::getAlertableColumns();

       return view('plc.machine_threshold.show', compact('machine', 'thresholds', 'availableColumns'));
   }

   /**
    * Form tạo cảnh báo mới cho máy
    */
   public function create($machine_id)
   {
       $machine = Machine::findOrFail($machine_id);
       $machines = Machine::active()->get();
       $availableColumns = PlcData::getAlertableColumns();

       return view('plc.machine_threshold.create', compact('machine', 'machines', 'availableColumns'));
   }

   /**
    * Lưu cảnh báo mới
    */
   public function store(Request $request)
   {
       $data = $request->validate($this->repository->validateCreate());

       try {
           // Set default values
           $data['status'] = $request->has('status');
           $data['show_on_chart'] = $request->has('show_on_chart');

           // Auto set name if empty
           if (empty($data['name'])) {
               $columns = PlcData::getAlertableColumns();
               $type = $data['type'] === 'boolean' ? 'boolean' : 'parameter';
               foreach ($columns[$type] as $col) {
                   if ($col['key'] === $data['plc_data_key']) {
                       $data['name'] = $col['label'];
                       break;
                   }
               }
           }

           $this->repository->create($data);

           return redirect()
               ->route('plc.machine.thresholds.show', $request->machine_id)
               ->with('success', 'Thêm cảnh báo thành công');

       } catch (\Exception $e) {
           return back()
               ->withInput()
               ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
       }
   }

   /**
    * Form sửa cảnh báo
    */
   public function edit($id)
   {
       $threshold = MachineThreshold::findOrFail($id);
       $machines = Machine::active()->get();
       $availableColumns = PlcData::getAlertableColumns();
    //    dd($threshold);

       return view('plc.machine_threshold.edit', compact('threshold', 'machines', 'availableColumns'));
   }

   /**
    * Cập nhật cảnh báo
    */
   public function update(Request $request, $id)
   {
       $threshold = MachineThreshold::findOrFail($id);
       $data = $request->validate($this->repository->validateUpdate($id));

       try {
           // Set checkbox values
           $data['status'] = $request->has('status');
           $data['show_on_chart'] = $request->has('show_on_chart');

        //    dd($data);
           $this->repository->update($data, $id);

           return redirect()
               ->route('plc.machine.thresholds.show', $threshold->machine_id)
               ->with('success', 'Cập nhật cảnh báo thành công');

       } catch (\Exception $e) {
           return back()
               ->withInput()
               ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
       }
   }

   /**
    * Xóa cảnh báo
    */
   public function destroy($id)
   {
       try {
           $threshold = MachineThreshold::findOrFail($id);
           $machine_id = $threshold->machine_id;

           $this->repository->delete($id);

           return redirect()
               ->route('plc.machine.thresholds.show', $machine_id)
               ->with('success', 'Xóa cảnh báo thành công');

       } catch (\Exception $e) {
           return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
       }
   }

   /**
    * Toggle hiển thị trên biểu đồ
    */
   public function toggleChart($id)
   {
       try {
           $threshold = MachineThreshold::findOrFail($id);
           $threshold->show_on_chart = !$threshold->show_on_chart;
           $threshold->save();

           return response()->json([
               'success' => true,
               'show_on_chart' => $threshold->show_on_chart
           ]);

       } catch (\Exception $e) {
           return response()->json([
               'success' => false,
               'message' => $e->getMessage()
           ], 500);
       }
   }

   /**
    * Toggle trạng thái active
    */
   public function toggleStatus($id)
   {
       try {
           $threshold = MachineThreshold::findOrFail($id);
           $threshold->status = !$threshold->status;
           $threshold->save();

           return response()->json([
               'success' => true,
               'status' => $threshold->status
           ]);

       } catch (\Exception $e) {
           return response()->json([
               'success' => false,
               'message' => $e->getMessage()
           ], 500);
       }
   }
}
