<?php

namespace App\Http\Controllers\Recruitment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Recruitment\RoleRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Validator;
use SebastianBergmann\GlobalState\Exception;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(RoleRepository $roleRepo)
    {
        $this->roleRepo = $roleRepo;
    }
    public function index()
    {
        $datas = $this->roleRepo->all();
        return view('recruitment/role/index', compact('datas'));
    }

    function getRoutesByGroup()
    {
        // $prefix = 'personnel';

        // $routes = collect(Route::getRoutes())->filter(function ($route) use ($prefix) {
        //     return Str::startsWith($route->uri, $prefix);
        // });

        $routeGroups = [];

        $routesCollection = Route::getRoutes()->getRoutesByName();
        $prefix = 'recruitment';

        foreach ($routesCollection as $route) {
            $routeAction = $route->getAction();
            $routeName = $route->getName();
            // 'recruitment.candidate.create'
            if ($routeAction && $routeAction['prefix'] == $prefix && $routeName != 'recruitment.index' && $routeName != 'recruitment.403') {
                $groupKey = explode('.', $routeName, 3);
                $groupKey = isset($groupKey[1]) ? str_replace('/', '.', $prefix) . '.' . $groupKey[1] . '.index' : null;
                if ($groupKey) {
                    $routeGroups[$groupKey][] = $routeName;
                }
            }
        }

       // dd($routeGroups);
        //dd($routeGroups);
        return $routeGroups;
    }

    function getRoutesByPersonnel()
    {
        // $prefix = 'personnel';

        // $routes = collect(Route::getRoutes())->filter(function ($route) use ($prefix) {
        //     return Str::startsWith($route->uri, $prefix);
        // });

        $routeGroups = [];

        $routesCollection = Route::getRoutes()->getRoutesByName();
        $prefix = 'personnel';

        foreach ($routesCollection as $route) {
            $routeAction = $route->getAction();
            $routeName = $route->getName();
            // 'recruitment.candidate.create'
            if ($routeAction && $routeAction['prefix'] == $prefix && $routeName != 'personnel.index') {
                $groupKey = explode('.', $routeName, 3);
                $groupKey = isset($groupKey[1]) ? str_replace('/', '.', $prefix) . '.' . $groupKey[1] . '.index' : null;
                if ($groupKey) {
                    $routeGroups[$groupKey][] = $routeName;
                }
            }
        }

       // dd($routeGroups);
        //dd($routeGroups);
        return $routeGroups;
    }

    public function create()
    {
        $routePersonnels = $this->getRoutesByPersonnel();
        $routeGroups = $this->getRoutesByGroup();
        return view('recruitment/role/create', compact('routeGroups', 'routePersonnels'));
    }
    public function store(Request $request)
    {
        try {
            $input = $request->except('selected_routes');
            $validator = Validator::make($input, $this->roleRepo->validateCreate());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $input['active'] = isset($input['active']) ? 1 : 0;
            $input['created_by'] = Auth::user()->id;
            $input['permissons'] = implode(",", $request->selected_routes);
            $role = $this->roleRepo->create($input);
            return redirect()->route('recruitment.role.index')->with('sucess', 'Tạo mới thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $data = $this->roleRepo->find($id);
        $routeGroups = $this->getRoutesByGroup();
        $routePersonnels = $this->getRoutesByPersonnel();
        $permisions = [];
        if ($data->permissons) {
            $permisions = explode(',', $data->permissons);
            $permisions = array_unique($permisions);
        }
        return view('recruitment.role.edit', compact('data', 'permisions', 'routeGroups', 'routePersonnels'));
    }
    public function update(Request $request, $id)
    {
        try {
            $input = $request->except('selected_routes');
            $validator = Validator::make($input, $this->roleRepo->validateUpdate($id));
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $input['permissons'] = implode(",", $request->selected_routes);
            $input['active'] = isset($input['active']) ? 1 : 0;
            $role = $this->roleRepo->update($input, $id);
            return redirect()->route('recruitment.role.index')->with('success', 'Cập nhật thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }
    public function destroy($id)
    {
        $this->roleRepo->delete($id);
        return redirect()->route('recruitment.role.index')->with('success', 'Xóa thành công');
    }
}
