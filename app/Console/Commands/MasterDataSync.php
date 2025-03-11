<?php

namespace App\Console\Commands;

use App\Models\Position;
use App\Models\Candidate;
use App\Models\RequestForm;
use App\Models\RecruitmentNeed;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MasterDataSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:masterdata-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Đồng bộ master data theo ngày';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dbs = ['sangkien'];
        foreach($dbs as $db)
        {

        }

        $this->syncUser();
        $this->syncPosition();
        $this->syncDepartment();
        $this->info('Đồng bộ dữ liệu kế hoạch tuyển dụng thành công');
    }

    private function syncUser()
    {

    }

    private function syncPosition()
    {

    }

    private function syncDepartment($db)
    {
        DB::connection($db)->table('department')->updateOrInsert(
            [
                'name' => $this->department->code,
            ],
            [
                'name' => $this->department->code,
                'display_name' => $this->department->name,
                //'head_of_department' => $this->department->manager_by,
            ]
        );
    }
}
