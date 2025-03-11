<?php

namespace App\Console\Commands;

use App\Models\Machine;
use App\Models\MachineThreshold;
use App\Services\PlcDataService;
use Illuminate\Console\Command;

class CrawlPlcDataCommand extends Command
{
    protected $signature = 'plc:crawl {--interval=5 : Thời gian giữa các lần crawl (giây)}';
    protected $description = 'Crawl dữ liệu từ PLC web interface';

    protected $plcDataService;

    public function __construct(PlcDataService $plcDataService)
    {
        parent::__construct();
        $this->plcDataService = $plcDataService;
    }

 public function handle()
{
    $interval = $this->option('interval');
    $this->info('Bắt đầu lấy dữ liệu');

    while(true) {
        try {
            // Lấy tất cả máy đang active
            $machines = Machine::where('status', true)->get();
            $this->info("\nTìm thấy " . $machines->count() . " máy đang hoạt động");

            foreach($machines as $machine) {
                $this->info("\nĐang lấy dữ liệu máy {$machine->name}...");

                $data = $this->plcDataService->crawlData($machine->id);

                if ($data) {
                    $this->info("Lưu dữ liệu máy {$machine->name} (ID: {$data->id}) thành công");

                    // Kiểm tra các ngưỡng
                    $thresholds = MachineThreshold::where('machine_id', $machine->id)
                        ->where('status', true)
                        ->get();

                    $this->info("Tìm thấy " . $thresholds->count() . " ngưỡng cần kiểm tra");

                    foreach($thresholds as $threshold) {
                        $plcDataKey = $threshold->plc_data_key;
                        $value = $data->{$plcDataKey};
                        $this->info("Kiểm tra {$threshold->name}:");
                        $this->info("- Key: {$plcDataKey}");
                        $this->info("- Giá trị hiện tại: {$value}");
                        $this->info("- Ngưỡng: Min={$threshold->min_value}, Max={$threshold->max_value}");
                        $this->info("- Cảnh báo: Min={$threshold->warning_min_value}, Max={$threshold->warning_max_value}");

                        if($threshold->type === 'boolean' && $value === true) {
                            $this->warn("⚠️ Phát hiện cảnh báo boolean: {$threshold->warning_message}");
                        }
                        else if($value > $threshold->max_value) {
                            $this->error("🔴 Vượt ngưỡng trên: {$value} > {$threshold->max_value}");
                        }
                        else if($value < $threshold->min_value) {
                            $this->error("🔴 Dưới ngưỡng dưới: {$value} < {$threshold->min_value}");
                        }
                        else if($value > $threshold->warning_max_value) {
                            $this->warn("⚠️ Gần ngưỡng trên: {$value}");
                        }
                        else if($value < $threshold->warning_min_value) {
                            $this->warn("⚠️ Gần ngưỡng dưới: {$value}");
                        }
                        else {
                            $this->info("✅ Giá trị bình thường");
                        }
                    }

                    // Hiển thị các thông số quan trọng
                    // $this->table(
                    //     ['Thông số', 'Giá trị'],
                    //     [
                    //         ['Ca', $data->ca],
                    //         ['Nhiệt độ nhựa', $data->nhiet_do_nhua],
                    //         ['Áp lực nhựa', $data->ap_luc_nhua],
                    //         ['Năng suất', $data->nang_suat],
                    //         ['Tốc độ VX', $data->toc_do_vx],
                    //         ['Tốc độ máy chỉ', $data->toc_do_may_chi],
                    //         ['Nhiệt độ nước vacuum 1', $data->nhiet_do_nuoc_vacuum_1],
                    //         ['Nhiệt độ nước vacuum 2', $data->nhiet_do_nuoc_vacuum_2]
                    //     ]
                    // );
                } else {
                    $this->error("Không thể lấy dữ liệu từ máy {$machine->name}");
                }
            }

        } catch (\Exception $e) {
            $this->error('Lỗi: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
        }

        if ($interval > 0) {
            $this->info("\nĐợi {$interval} giây...");
            sleep($interval);
        } else {
            break;
        }
    }
}
}
