<?php

namespace App\Http\Controllers\Recruitment;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Recruitment\AccountRepository;
use App\Enums\RecruitmentChangeEnum;

class AccountController extends Controller
{
    public function __construct(AccountRepository $accountRepo) {
        $this->accountRepo = $accountRepo;
    }

    public function index()
    {
        $values = RecruitmentChangeEnum::cases();
        return view('recruitment/account/index', compact('values'));
    }

    public function create()
    {
        return view('recruitment/account/create');
    }

    public function store()
    {
        return view('recruitment/account/create');
    }

    public function sendMail()
    {
        return view('recruitment/account/create');
    }

    public function upload(Request $request): JsonResponse
    {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;

            $request->file('upload')->move(public_path('images'), $fileName);

            $url = asset('media/' . $fileName);

            return response()->json(['fileName' => $fileName, 'uploaded'=> 1, 'url' => $url]);
        }
    }

    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

}
