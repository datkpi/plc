<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Candidate;
use App\Models\Position;
use App\Models\RecruitmentNeed;
use App\Models\RequestForm;

class RecruitmentSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:recruitment-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Đồng bộ dữ liệu kế hoạch tuyển dụng theo tháng';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $startYear = 2018; // Khởi đầu từ tháng 1 năm 2018
        $startMonth = 1;
        $endYear = now()->year;
        $endMonth = now()->month;

        // Lặp qua các năm từ 2018 đến hiện tại
        for ($year = $startYear; $year <= $endYear; $year++) {
            // Lặp qua từng tháng trong năm
            for ($month = $startMonth; $month <= ($year < $endYear ? 12 : $endMonth); $month++) {
                foreach (Position::all() as $position) {
                    $this->processPositionForMonthYear($position, $month, $year); // Đồng bộ lại dữ liệu theo tháng/năm/vị trí
                }
            }
            $startMonth = 1; // Bắt đầu từ tháng 1 nếu qua năm mới
        }

        $this->info('Đồng bộ dữ liệu kế hoạch tuyển dụng thành công');
    }

    private function processPositionForMonthYear($position, $month, $year)
    {
        $position_id = $position->id;

        // Tính nhau cầu phát sinh
        $more = $this->calculateMore($position_id, $month, $year);

        // Tính số lượng đã tuyển
        $recruitmented = $this->calculateRecruited($position_id, $month, $year);

        // Tính nhu cầu đầu kì
        $need = $this->calculateNeed($position_id, $month, $year);

        // Giảm trừ
        $sub = $this->calculateSub($position->id, $month, $year);
        $totalNeed = $need + $more - $recruitmented - $sub;

        $recruitmentNeed = RecruitmentNeed::firstOrCreate(
            ['position_id' => $position_id, 'time' => "$year-$month-01"],
            ['need' => $need, 'more' => $more, 'recruitmented' => $recruitmented, 'total' => $totalNeed, 'active' => true]
        );

        $isNewRecord = $recruitmentNeed->wasRecentlyCreated;

        if (
            !$isNewRecord &&
            ($recruitmentNeed->need !== $need ||
                $recruitmentNeed->more !== $more ||
                $recruitmentNeed->recruitmented !== $recruitmented ||
                $recruitmentNeed->sub !== $sub ||
                $recruitmentNeed->total !== $totalNeed)
        ) {
            $recruitmentNeed->update([
                'need' => $need,
                'more' => $more,
                'recruitmented' => $recruitmented,
                'sub' => $sub,
                'total' => $totalNeed,
            ]);
        }
    }

    private function calculateMore($position_id, $month, $year)
    {
        return RequestForm::whereYear('request_date', $year)
            ->whereMonth('request_date', $month)
            ->whereNotIn('status', ['plan', 'cancel'])
            ->where('position_id', $position_id)
            ->sum('quantity') ?? 0;
    }

    private function calculateSub($position_id, $month, $year)
    {
        return RequestForm::whereYear('request_date', $year)
            ->whereMonth('request_date', $month)
            ->where('position_id', $position_id)
            ->sum('sub') ?? 0;
    }

    // private function calculateRecruited($position_id, $month, $year)
    // {
    //     return Candidate::whereYear('probation_to', $year)
    //         ->whereMonth('probation_to', $month)

    //         ->where('position_id', $position_id)
    //         ->whereIn('status', ['recruitment_success', 'probation_success', 'probation_fail', 'probation_long', 'employee'])
    //         ->count();
    // }

//    private function calculateRecruited($position_id, $month, $year)
// {
//     return Candidate::where(function ($query) use ($year, $month) {
//         $query->where(function ($query) use ($year, $month) {
//             $query->whereYear('probation_from', $year)
//                   ->whereMonth('probation_from', $month)
//                   ->where('status', 'recruitment_success');
//         })
//         ->orWhere(function ($query) use ($year, $month) {
//             $query->whereYear('probation_to', $year)
//                   ->whereMonth('probation_to', $month)
//                   ->whereIn('status', ['probation_success', 'probation_fail', 'employee']);
//         });
//     })
//     ->where('position_id', $position_id)
//     ->count();
// }

    private function calculateRecruited($position_id, $month, $year)
    {
        return Candidate::where(function ($query) use ($year, $month) {
            $query->where(function ($query) use ($year, $month) {
                $query->whereYear('probation_from', $year)
                    ->whereMonth('probation_from', $month)
                    ->where('status', 'recruitment_success');
            })
                ->orWhere(function ($query) use ($year, $month) {
                    $query->whereYear('probation_to', $year)
                        ->whereMonth('probation_to', $month)
                        ->whereIn('status', ['probation_success', 'probation_fail', 'employee']);
                });
        })
            ->where('position_id', $position_id)
            ->count();
    }


    private function calculateNeed($position_id, $month, $year)
    {
        if ($month == 1) {
            $prevMonth = 12;
            $prevYear = $year - 1;
        } else {
            $prevMonth = $month - 1;
            $prevYear = $year;
        }

        $previousNeed = RecruitmentNeed::whereYear('time', $prevYear)
            ->whereMonth('time', $prevMonth)
            ->where('position_id', $position_id)
            ->first();

        return $previousNeed ? ($previousNeed->need + $previousNeed->more - $previousNeed->recruitmented - $previousNeed->sub) : 0;
    }
}
