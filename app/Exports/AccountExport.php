<?php

namespace App\Exports;

use App\Models\Account;
use Maatwebsite\Excel\Concerns\FromCollection;

class AccountExport implements FromCollection
{
    public function __construct(int $id) {
    	$this->id = $id;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return Account::all();
    // }

    public function collection()
    {
        return ClassSubject::findOrFail($this->id);
    }

    //Tiêu đề cho bảng
    public function headings() :array {
    	return ["STT", "Tên tài khoản", "Mật khẩu", "Trạng thái"];
    }

    //Xuất dựa trên blade view
    public function view(): View
    {
        return view('exports.users', [
            'users' => User::all()
        ]);
    }
}
