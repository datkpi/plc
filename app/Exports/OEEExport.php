<?php

namespace App\Exports;

use App\Services\OEECalculationService;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class OEEExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $machineId;
    protected $dateParam;
    protected $type;
    protected $method;
    protected $oeeService;

    public function __construct($machineId, $dateParam, $type = 'daily', $method = 'product')
    {
        $this->machineId = $machineId;
        $this->dateParam = $dateParam;
        $this->type = $type;
        $this->method = $method;
        $this->oeeService = new OEECalculationService();
    }

    public function collection()
    {
        if ($this->type == 'daily') {
            $data = $this->oeeService->calculateDailyOEE($this->machineId, $this->dateParam);
            $collection = collect();

            foreach ($data['shifts'] as $shift => $oee) {
                $collection->push([
                    'shift' => $shift,
                    'availability' => $oee['availability'],
                    'performance' => $oee['performance'],
                    'quality' => $oee['quality'],
                    'oee' => $oee['oee']
                ]);
            }

            // Add daily average
            $collection->push([
                'shift' => 'Average',
                'availability' => $data['daily']['availability'],
                'performance' => $data['daily']['performance'],
                'quality' => $data['daily']['quality'],
                'oee' => $data['daily']['oee']
            ]);

            return $collection;
        } elseif ($this->type == 'monthly') {
            list($year, $month) = explode('-', $this->dateParam);

            if ($this->method == 'product') {
                $data = $this->oeeService->calculateMonthlyOEE($this->machineId, $year, $month);
                $collection = collect();

                foreach ($data['daily'] as $date => $oee) {
                    $collection->push([
                        'date' => $date,
                        'availability' => $oee['availability'],
                        'performance' => $oee['performance'],
                        'quality' => $oee['quality'],
                        'oee' => $oee['oee']
                    ]);
                }

                // Add monthly average
                $collection->push([
                    'date' => 'Average',
                    'availability' => $data['monthly']['availability'],
                    'performance' => $data['monthly']['performance'],
                    'quality' => $data['monthly']['quality'],
                    'oee' => $data['monthly']['oee']
                ]);

                return $collection;
            } else {
                $data = $this->oeeService->calculateMonthlyOEEByDesign($this->machineId, $year, $month);
                return collect([
                    [
                        'metric' => 'Availability',
                        'value' => $data['availability'],
                        'calculation' => $data['details']['run_time'] . ' / ' . $data['details']['total_time']
                    ],
                    [
                        'metric' => 'Performance',
                        'value' => $data['performance'],
                        'calculation' => $data['details']['hourly_output'] . ' / ' . $data['details']['design_capacity']
                    ],
                    [
                        'metric' => 'Quality',
                        'value' => $data['quality'],
                        'calculation' => $data['details']['total_good'] . ' / ' . $data['details']['total_output']
                    ],
                    [
                        'metric' => 'OEE',
                        'value' => $data['oee'],
                        'calculation' => 'A × P × Q'
                    ]
                ]);
            }
        }

        // Default empty collection
        return collect();
    }

    public function headings(): array
    {
        if ($this->type == 'daily') {
            return ['Ca', 'Availability (%)', 'Performance (%)', 'Quality (%)', 'OEE (%)'];
        } elseif ($this->type == 'monthly') {
            if ($this->method == 'product') {
                return ['Ngày', 'Availability (%)', 'Performance (%)', 'Quality (%)', 'OEE (%)'];
            } else {
                return ['Chỉ số', 'Giá trị (%)', 'Tính toán'];
            }
        }

        return [];
    }

    public function map($row): array
    {
        if ($this->type == 'daily') {
            return [
                $row['shift'] === 'Average' ? 'Trung bình ngày' : 'Ca ' . $row['shift'],
                number_format($row['availability'] * 100, 1),
                number_format($row['performance'] * 100, 1),
                number_format($row['quality'] * 100, 1),
                number_format($row['oee'] * 100, 1)
            ];
        } elseif ($this->type == 'monthly') {
            if ($this->method == 'product') {
                return [
                    $row['date'] === 'Average' ? 'Trung bình tháng' : Carbon::parse($row['date'])->format('d/m/Y'),
                    number_format($row['availability'] * 100, 1),
                    number_format($row['performance'] * 100, 1),
                    number_format($row['quality'] * 100, 1),
                    number_format($row['oee'] * 100, 1)
                ];
            } else {
                return [
                    $row['metric'],
                    number_format($row['value'] * 100, 1),
                    $row['calculation']
                ];
            }
        }

        return [];
    }

    public function title(): string
    {
        if ($this->type == 'daily') {
            return 'OEE Ngày ' . Carbon::parse($this->dateParam)->format('d/m/Y');
        } elseif ($this->type == 'monthly') {
            list($year, $month) = explode('-', $this->dateParam);
            return 'OEE Tháng ' . $month . '/' . $year;
        }

        return 'OEE Report';
    }
}
