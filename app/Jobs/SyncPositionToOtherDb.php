<?php

namespace App\Jobs;

use App\Models\Position;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SyncPositionToOtherDb implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $position;
    protected $database;
    protected $action;
    /**
     * Create a new job instance.
     */
    public function __construct(Position $position, $database, $action)
    {
        $this->position = $position;
        $this->database = $database;
        $this->action = $action;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            DB::connection($this->database)->beginTransaction();

            // Logic đồng bộ dữ liệu cho cơ sở dữ liệu hiện tại
            $this->syncToDatabase();

            DB::connection($this->database)->commit();
            Log::info("Đồng bộ user thành công cho DB {$this->database}");
        } catch (\Exception $e) {
            DB::connection($this->database)->rollBack();
            Log::error("Lỗi đồng bộ user cho DB {$this->database}", ['error' => $e->getMessage()]);
            // Xử lý thêm nếu cần
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
            $data = [
                'display_name' => $this->position->name,
                'update_at' => Carbon::now(),
                'name' => $this->position->code,
            ];

            if ($this->action == 'create') {
                $data['create_at'] = Carbon::now();
                DB::connection($this->database)->table('position')->insert($data);
            } else if ($this->action == 'update') {
                DB::connection($this->database)->table('position')
                    ->where('name', $this->position->code)
                    ->update($data);
            }
        } catch (\Exception $e) {
            // Xử lý lỗi nếu cần
            throw $e; // Ném lại ngoại lệ để xử lý lỗi ở nơi gọi
        }
    }
}
