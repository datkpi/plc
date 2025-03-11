<?php

namespace App\Http\Controllers\Recruitment;

use App\Enums\GenderEnum;
use App\Repositories\Recruitment\UserRepository;
use Exception;
use Illuminate\Http\Request;
use App\Common\StringHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;
use Validator;

class ActivityController extends Controller
{

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }
    function getModelNames(): array
    {
        $path = app_path('Models') . '/*.php';
        return collect(glob($path))->map(fn($file) => basename($file, '.php'))->toArray();
    }
    public function index(Request $request)
    {

        $models = $this->getModelNames();
        $users = StringHelpers::getSelectOptions($this->userRepo->all());
        $query = Activity::query();
        if (isset($request->event)) {
            $query->where('event', $request->input('event'));
        }

        if (isset($request->model)) {
            $modelClass = 'App\Models\\' . $request->model;
            $query->where('subject_type', $modelClass);
        }

        // Lọc theo người thao tác (causer)
        if (isset($request->causer)) {
            $query->whereHas('causer', function ($q) use ($request) {
                $q->where('id', $request->causer);
            });
        }

        $datas = $query->paginate(15);

        //dd($datas);
        return view('recruitment/activity/index', compact('datas', 'users', 'models'));
    }

    public function show($id)
    {
        $data = Activity::find($id);
        if (!$data) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại ');
        }

        $differences = [];

        // Check if old_values is set before iterating
        if ($data->old_values) {
            foreach ($data->attributes as $key => $newValue) {
                $oldValue = $data->old_values[$key] ?? null;

                if ($oldValue !== $newValue) {
                    $differences[$key] = [
                        'old' => $oldValue,
                        'new' => $newValue,
                    ];
                }
            }
        }

        return view('recruitment.activity.show', compact('data', 'differences'));
    }



}
