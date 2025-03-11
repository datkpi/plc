<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SyncUserToOtherDb implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $database;
    protected $action;

    /**
     * Tạo một instance mới của job.
     *
     * @param  \App\Models\User  $user
     * @param  string  $database
     * @return void
     */
    public function __construct(User $user, $database, $action)
    {
        $this->user = $user;
        $this->database = $database;
        $this->action = $action;
    }

    /**
     * Thực hiện job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            DB::connection($this->database)->beginTransaction();

            // Logic đồng bộ dữ liệu cho cơ sở dữ liệu hiện tại
            $this->syncToDatabase();

            DB::connection($this->database)->commit();
            Log::info("Đồng bộ user thành công cho DB {$this->database}", ['user_id' => $this->user->id]);
        } catch (\Exception $e) {
            // Nếu có lỗi trong quá trình xử lý thì rollback lại các thay đổi
            DB::connection($this->database)->rollBack();
            Log::error("Lỗi đồng bộ user cho DB {$this->database}", ['user_id' => $this->user->id, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Đồng bộ dữ liệu người dùng với cơ sở dữ liệu.
     *
     * @return void
     */

    private function syncToDatabase()
    {
        try {

           $data =
                [
                    'display_name' => $this->user->name,
                    'employeeNumber'=> $this->user->code,
                    'email' => $this->user->email,
                    'taxCode' => $this->user->tax_code,
                    'sex' => $this->user->gender === 'male' ? 'Nam' : 'Nữ',
                    'date_of_birth' => $this->user->birthday,
                    'type_of_contract' => $this->user->contract_type,
                    'termination_retirement' => $this->user->end_job,
                    'phone_Number' => $this->user->phone_number,
                    'cccd' => $this->user->cccd,
                    'jobTitle' => optional($this->user->position)->name,
                    'cccd_issuer' => $this->user->cccd_issuer,
                    'cccd_date' => $this->user->cccd_date,
                    'contract_start' => $this->user->contract_start,
                    'contract_end' => $this->user->contract_end,
                    'department' => optional($this->user->department)->code,
                    'username' => $this->user->code,
                    'password_hash' => $this->user->password,
                    'block' => optional(optional($this->user->department)->parent)->code,
                    'status' => 'NTP',
                    'update_at' => Carbon::now(),
                ];
                //dd($data);

            if ($this->action == 'create') {
                $data['create_at'] = Carbon::now();
                DB::connection($this->database)->table('users')->insert($data);
            } else if ($this->action == 'update') {
                DB::connection($this->database)->table('users')
                    ->where('employeeNumber', $this->user->code)
                    ->update($data);
            }

        } catch (\Exception $e) {
            // Xử lý lỗi nếu cần
            throw $e; // Ném lại ngoại lệ để xử lý lỗi ở nơi gọi
        }
    }
}
