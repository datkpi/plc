<?php

namespace App\Services;

use App\Models\PlcData;
use App\Models\ProductionEntry;
use App\Models\Product;
use App\Models\Machine;
use App\Models\PeCoilStandard;
use Carbon\Carbon;

class OEECalculationService
{
    /**
     * Tính OEE theo ca (chia theo sản phẩm)
     */
    public function calculateShiftOEE($machineId, $date, $shift, $runTimeMinutes = null)
    {
        // Lấy dữ liệu từ plc_data và production_entries
        $plcShift = $shift;

        // Dữ liệu PLC - lấy theo máy, ngày, ca
        $plcData = PlcData::where('machine_id', $machineId)
            ->whereDate('datalog_date', $date)
            ->where('datalog_data_ca', $plcShift)
            ->orderBy('id', 'desc')
            ->get();

        // Dữ liệu sản xuất - lấy theo máy, ngày, ca
        $entry = ProductionEntry::where([
            'machine_id' => $machineId,
            'date' => $date,
            'shift' => $shift
        ])->first();

        if (!$entry || $plcData->isEmpty()) {
            return [
                'availability' => 0,
                'performance' => 0,
                'quality' => 0,
                'oee' => 0,
                'details' => []
            ];
        }

        // A: Thời gian chạy máy / 8h
        if ($runTimeMinutes === null) {
            $lastRecord = $plcData->first(); // first() vì đã orderBy desc
            // Đổi từ giờ sang phút
            $runTimeMinutes = $lastRecord->datalog_data_gio_chay_2;
        }
        $availability = $runTimeMinutes / (8 * 60); // 8h = 480 phút
        $availability = min(1, $availability); // Đảm bảo không vượt quá 100%

        // P: Năng suất thực tế / Năng suất định mức theo SP
        // - Năng suất thực tế từ nang_suatkg_h
        $actualProductivity = $plcData->avg('nang_suatkg_h') ?? 0;

        // - Lấy năng suất định mức từ sản phẩm
        $product = Product::where('code', $entry->product_code)->first();
        $targetProductivity = $product ? $product->min_productivity : 0;

        // Nếu không có định mức từ sản phẩm, thử lấy từ entry
        if ($targetProductivity <= 0) {
            $targetProductivity = $entry->target_productivity ?? 0;
        }

        // Nếu vẫn không có, sử dụng giá trị mặc định
        if ($targetProductivity <= 0) {
            $targetProductivity = 1; // Để tránh lỗi chia cho 0
        }

        $performance = $actualProductivity / $targetProductivity;
        $performance = min(1, $performance); // Đảm bảo không vượt quá 100%

        // Q: Chính phẩm / (Chính phẩm + Phế phẩm) - tính dựa trên kg
        // Quy đổi chính phẩm (cây/cuộn) sang kg
        $goodProductsKg = $this->convertToKg($entry->product_code, $entry->good_quantity);

        // Phế phẩm đã là kg
        $defectProductsKg = $entry->defect_weight;

        // Tính Quality
        $totalProductsKg = $goodProductsKg + $defectProductsKg;
        $quality = $totalProductsKg > 0 ? $goodProductsKg / $totalProductsKg : 0;
        $quality = min(1, $quality); // Đảm bảo không vượt quá 100%

        // OEE = A × P × Q
        $oee = $availability * $performance * $quality;

        return [
            'availability' => $availability,
            'performance' => $performance,
            'quality' => $quality,
            'oee' => $oee,
            'details' => [
                'run_time_minutes' => $runTimeMinutes,
                'actual_productivity' => $actualProductivity,
                'target_productivity' => $targetProductivity,
                'good_products' => $entry->good_quantity, // cây/cuộn
                'good_products_kg' => $goodProductsKg,
                'defect_products_kg' => $defectProductsKg,
                'total_products_kg' => $totalProductsKg
            ]
        ];
    }

    /**
     * Tính OEE theo ngày (chỉ chia theo sản phẩm)
     */
    public function calculateDailyOEE($machineId, $date)
    {
        // Lấy dữ liệu của 3 ca
        $shifts = ['CA1', 'CA2', 'CA3'];
        $shiftResults = [];

        // Dữ liệu tổng hợp
        $totalRunTimeMinutes = 0;
        $totalGoodProductsKg = 0;
        $totalDefectProductsKg = 0;
        $validShifts = 0;

        // Tính OEE cho từng ca và tổng hợp số liệu
        foreach($shifts as $shift) {
            $shiftData = $this->calculateShiftOEE($machineId, $date, $shift);
            $shiftResults[$shift] = $shiftData;

            // Cộng dồn thời gian chạy và sản phẩm
            if ($shiftData['oee'] > 0) {
                $validShifts++;
                $totalRunTimeMinutes += $shiftData['details']['run_time_minutes'];
                $totalGoodProductsKg += $shiftData['details']['good_products_kg'];
                $totalDefectProductsKg += $shiftData['details']['defect_products_kg'];
            }
        }

        $totalProductsKg = $totalGoodProductsKg + $totalDefectProductsKg;

        // Tính OEE ngày
        if ($validShifts > 0) {
            // A: Thời gian chạy máy / (8h * số ca có sản xuất)
            $plannedTime = $validShifts * 8 * 60; // 8h mỗi ca
            $availability = $plannedTime > 0 ? $totalRunTimeMinutes / $plannedTime : 0;
            $availability = min(1, $availability);

            // P: Tính trung bình có trọng số của Performance các ca
            $weightedPerformance = 0;
            $totalWeight = 0;
            foreach($shifts as $shift) {
                if ($shiftResults[$shift]['oee'] > 0) {
                    $weight = $shiftResults[$shift]['details']['run_time_minutes'];
                    $weightedPerformance += $shiftResults[$shift]['performance'] * $weight;
                    $totalWeight += $weight;
                }
            }
            $performance = $totalWeight > 0 ? $weightedPerformance / $totalWeight : 0;

            // Q: Tổng chính phẩm (kg) / Tổng sản phẩm (kg) - 3 ca
            $quality = $totalProductsKg > 0 ? $totalGoodProductsKg / $totalProductsKg : 0;

            // OEE = A × P × Q
            $oee = $availability * $performance * $quality;
        } else {
            $availability = 0;
            $performance = 0;
            $quality = 0;
            $oee = 0;
        }

        return [
            'shifts' => $shiftResults,
            'daily' => [
                'availability' => $availability,
                'performance' => $performance,
                'quality' => $quality,
                'oee' => $oee
            ],
            'details' => [
                'valid_shifts' => $validShifts,
                'total_run_time_minutes' => $totalRunTimeMinutes,
                'total_good_products_kg' => $totalGoodProductsKg,
                'total_defect_products_kg' => $totalDefectProductsKg,
                'total_products_kg' => $totalProductsKg
            ]
        ];
    }

    /**
     * Tính OEE theo tháng (Phương pháp 1: Lấy trung bình từ OEE ngày)
     */
    public function calculateMonthlyOEE($machineId, $year, $month)
    {
        $startDate = Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();
        $dailyOEEs = [];

        // Tính OEE cho từng ngày trong tháng
        for($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dailyResult = $this->calculateDailyOEE($machineId, $date->format('Y-m-d'));

            // Chỉ tính những ngày có dữ liệu
            if ($dailyResult['daily']['oee'] > 0) {
                $dailyOEEs[$date->format('Y-m-d')] = $dailyResult['daily'];
            }
        }

        // Tính trung bình OEE tháng từ các ngày
        if (count($dailyOEEs) > 0) {
            $monthlyOEE = [
                'availability' => collect($dailyOEEs)->avg('availability'),
                'performance' => collect($dailyOEEs)->avg('performance'),
                'quality' => collect($dailyOEEs)->avg('quality')
            ];

            // OEE = A × P × Q (trung bình APQ từ các ngày)
            $monthlyOEE['oee'] = $monthlyOEE['availability'] * $monthlyOEE['performance'] * $monthlyOEE['quality'];
        } else {
            $monthlyOEE = [
                'availability' => 0,
                'performance' => 0,
                'quality' => 0,
                'oee' => 0
            ];
        }

        return [
            'daily' => $dailyOEEs,
            'monthly' => $monthlyOEE
        ];
    }

     /**
     * Tính OEE theo tháng (Phương pháp không chia theo sản phẩm)
     */
    public function calculateMonthlyOEEByDesign($machineId, $year, $month, $params)
    {
        // Input từ form
        $totalMonthTime = $params['total_month_time'] ?? 0;    // Thời gian tháng (phút)
        $unplannedTime = $params['unplanned_time'] ?? 0;       // Thời gian không có kế hoạch (phút)
        $designCapacity = $params['design_capacity'] ?? 0;      // Năng suất thiết kế (kg/h)

        // Thời gian có kế hoạch = Thời gian tháng - Thời gian không có kế hoạch
        $plannedTime = $totalMonthTime - $unplannedTime;

        // Lấy thời gian chạy máy từ PLC data (tổng giờ chạy cuối của mỗi ca)
        $totalRunTime = 0;
        $shifts = ["CA1", "CA2", "CA3"];
        $startDate = Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        for($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            foreach($shifts as $shift) {
                $lastRecord = PlcData::where('machine_id', $machineId)
                    ->whereDate('datalog_date', $date->format('Y-m-d'))
                    ->where('datalog_data_ca', $shift)
                    ->orderBy('id', 'desc')
                    ->first();

                if ($lastRecord) {
                    $totalRunTime += $lastRecord->datalog_data_gio_chay_2;
                }
            }
        }

        // A = Thời gian chạy / Thời gian có kế hoạch
        $availability = $plannedTime > 0 ? min(1, $totalRunTime / $plannedTime) : 0;

        // Lấy dữ liệu sản xuất trong tháng
        $entries = ProductionEntry::where('machine_id', $machineId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();

        $totalOutputQuantity = 0;     // Tổng ra máy (cây/cuộn)
        $totalGoodQuantity = 0;       // Tổng chính phẩm (cây/cuộn)
        $totalDefectWeight = 0;       // Tổng phế phẩm (kg)
        $totalWasteWeight = 0;        // Tổng phế liệu (kg)

        foreach($entries as $entry) {
            $totalOutputQuantity += $entry->output_quantity;
            $totalGoodQuantity += $entry->good_quantity;
            $totalDefectWeight += $entry->defect_weight;
            $totalWasteWeight += $entry->waste_weight;
        }

        // Chuyển đổi số lượng sang kg
        $totalOutputKg = 0;
        $totalGoodKg = 0;
        foreach($entries as $entry) {
            $totalOutputKg += $this->convertToKg($entry->product_code, $entry->output_quantity);
            $totalGoodKg += $this->convertToKg($entry->product_code, $entry->good_quantity);
        }

        // P = (Tổng sản phẩm ra máy / Thời gian chạy) / Năng suất thiết kế
        $totalRunTimeHours = $totalRunTime / 60; // Chuyển phút sang giờ
        $actualHourlyRate = $totalRunTimeHours > 0 ? $totalOutputKg / $totalRunTimeHours : 0;
        $performance = $designCapacity > 0 ? min(1, $actualHourlyRate / $designCapacity) : 0;

        // Q = Khối lượng chính phẩm / Tổng khối lượng ra máy
        $quality = $totalOutputKg > 0 ? min(1, $totalGoodKg / $totalOutputKg) : 0;

        // OEE = A × P × Q
        $oee = $availability * $performance * $quality;

        return [
            'availability' => $availability,
            'performance' => $performance,
            'quality' => $quality,
            'oee' => $oee,
            'details' => [
                'total_month_time' => $totalMonthTime,
                'unplanned_time' => $unplannedTime,
                'planned_time' => $plannedTime,
                'total_run_time' => $totalRunTime,
                'total_run_time_hours' => $totalRunTimeHours,
                // Số lượng (cây/cuộn)
                'total_output_quantity' => $totalOutputQuantity,
                'total_good_quantity' => $totalGoodQuantity,
                // Khối lượng (kg)
                'total_output_kg' => $totalOutputKg,
                'total_good_kg' => $totalGoodKg,
                'total_defect_weight' => $totalDefectWeight,
                'total_waste_weight' => $totalWasteWeight,
                'total_waste' => $totalDefectWeight + $totalWasteWeight,
                // Năng suất
                'actual_hourly_rate' => $actualHourlyRate,
                'design_capacity' => $designCapacity
            ]
        ];
    }

    /**
     * Chuyển đổi số lượng chính phẩm (cây/cuộn) sang kg sử dụng tên sản phẩm
     * @param string $productCode Mã sản phẩm
     * @param float $quantity Số lượng (cây/cuộn)
     * @return float Khối lượng (kg)
     */
    protected function convertToKg($productCode, $quantity)
    {
        if (!$productCode || $quantity <= 0) {
            return 0;
        }

        // 1. Lấy thông tin sản phẩm từ database
        $product = Product::where('code', $productCode)->first();
        if (!$product) {
            return 0;
        }

        // 2. Lấy định mức g/m
        $weightPerMeter = $product->gm_spec; // g/m
        if (!$weightPerMeter) {
            return 0;
        }

        // 3. Bóc tách tên sản phẩm để lấy thông tin
        $productName = $product->name;
        $parts = explode(' ', $productName);

        if (count($parts) < 3) {
            return 0; // Tên sản phẩm không đúng định dạng
        }

        $diameter = intval($parts[0]);            // "110 PN6 PE80" => 110
        $material = $parts[2];                    // "110 PN6 PE80" => PE80

        // 4. Lấy chiều dài tiêu chuẩn từ bảng pe_coil_standards hoặc từ quy tắc
        $standardLength = $this->getStandardLength($diameter, $material);

        // 5. Tính tổng số mét
        $totalMeters = $quantity * $standardLength;

        // 6. Tính tổng khối lượng (kg)
        $weightInKg = ($totalMeters * $weightPerMeter) / 1000;

        return $weightInKg;
    }

    /**
     * Lấy chiều dài tiêu chuẩn từ database hoặc quy tắc
     * @param int $diameter Đường kính ống
     * @param string $material Loại vật liệu (PE80, PE100, PPR)
     * @return int Chiều dài tiêu chuẩn (m)
     */
    protected function getStandardLength($diameter, $material)
    {
        // 1. Lấy từ database nếu có
        $standard = PeCoilStandard::where('diameter', $diameter)->first();
        if ($standard && $standard->length > 0) {
            return $standard->length;
        }

        // 2. Áp dụng quy tắc nếu không có trong database

        // PPR: mặc định 4m
        if ($material == 'PPR') {
            return 4;
        }

        // PE80, PE100: dựa vào đường kính
        if ($material == 'PE80' || $material == 'PE100') {
            // PE DN ≤ 90mm: theo tiêu chuẩn cuộn
            if ($diameter <= 90) {
                $defaultLengths = [
                    16 => 300,
                    20 => 300,
                    25 => 300,
                    32 => 200,
                    40 => 100,
                    50 => 100,
                    63 => 50,
                    75 => 25,
                    90 => 25
                ];

                return $defaultLengths[$diameter] ?? 100;
            }
            // PE DN ≥ 110mm: mặc định 6m
            else {
                return 6;
            }
        }

        // PSU: mặc định 6m
        if (strpos($material, 'PSU') !== false) {
            return 6;
        }

        return 100; // Mặc định nếu không xác định được
    }
}
