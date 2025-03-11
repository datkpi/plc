<?php

namespace App\Http\Controllers\Auth;

use App\Common\Traits\ApiResponses;
use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class AuthController extends Controller
{

    use ApiResponses;
    public function index()
    {
        return view('auth.login');
    }

    public function getAuthData()
    {
        try {
            return $this->success(Auth::user());
        } catch (\Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }

    public function getLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'code' => 'required|max:32',
            'password' => 'required|min:6|max:32',
        ]);


        if (Auth::attempt(['code' => $request->code, 'password' => $request->password, 'active' => 1])) {
            return redirect()->route('module.index');
        }

        return redirect()->back()->with('error', 'Sai thông tin đăng nhập hoặc tài khoản này đã bị vô hiệu');
    }



    public function registration()
    {
        return view('auth.registration');
    }


    public function customRegistration(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $data = $request->all();
        $check = $this->create($data);

        return redirect("dashboard")->withSuccess('You have signed-in');
    }


    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();

        return Redirect('login');
    }
}
