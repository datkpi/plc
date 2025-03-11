<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\DB;

class Admin
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $current_route = $request->route()->getName();

        if (strpos($current_route, 'create') !== false) {
            $parent_route = str_replace('create', 'index', $current_route);
            $method = 'create';
        } elseif (strpos($current_route, 'edit') !== false) {
            $parent_route = str_replace('edit', 'index', $current_route);
            $method = 'edit';
        }
        elseif (strpos($current_route, 'index') !== false) {
            $parent_route = str_replace('index', 'create', $current_route);
            //dd($parent_route);
            if(\Route::has($parent_route))
            {
                $method = 'create';
            }
            else{
                $method = 'index';
            }
        }
        else {
            $parent_route = null;
            $method = 'index';
        }


        if (!is_null(Auth::user())) {
            if (Auth::user()->is_root == false && $current_route != 'recruitment.index' && $current_route != 'recruitment.403') {
                $permissions = DB::table('role')->whereIn('id', explode(',', Auth::user()->role_id))->get()->pluck('permissons');
                if ($permissions) {
                    foreach ($permissions as $permission) {
                        if (strpos($permission, $current_route) === false) {
                            //return view('recruitment.abort.403');
                            abort(403, 'Bạn không có quyền truy cập chức năng này');
                        }
                    }
                } else {
                    //return view('recruitment.abort.403');
                    abort(403, 'Bạn không có quyền truy cập chức năng này');
                }
            }
            if ($parent_route == null) {
                $parent_route = $current_route;
            }

            // dd($method);
            \View::share(['current_route' => $current_route, 'parent_route' => $parent_route, 'method' => $method]);
            return $next($request);

        } else {
            return redirect()->route('auth.login');
        }
    }

}
