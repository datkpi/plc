<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportCandidate implements FromCollection, WithHeadings
{
    protected $data;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            'Họ tên',
            'Ngày sinh',
            'Trạng thái',
            'Vị trí',
            'Phòng ban',
            'Thời gian nhận hồ sơ',
            'Người nhận hồ sợ',
            'Nguồn tuyển dụng',
            'Ghi chú mối quan hệ',
            'Giới tính',
            'Số điện thoại',
            'Email',
            'Hộ khẩu',
            'Địa chỉ',
            'Địa chỉ chi tiết',
            'Quá trình đào tạo',
            'Kinh nghiệm làm việc',
            'Ngoại ngữ',
            'Phần mềm đặc thù',
            'Thông tin tham chiếu',
            'Ngày SLHS',
            'Người SLHS',
            'Nhận xét SLHS',
            'Kết quả SLHS',
            'Ngày PVSB',
            'Người PVSB',
            'Nhận xét PVSB',
            'Kết quả PVSB',
            'Ngày PVV1',
            'Người PVV1',
            'Nhận xét PVV1',
            'Kết quả PVV1',
            'Ngày PVV2',
            'Người PVV2',
            'Nhận xét PVV2',
            'Kết quả PVV2',
            'Ngày PVV3',
            'Người PVV3',
            'Nhận xét PVV3',
            'Kết quả PVV3',
            'Kết quả nhận việc',
            'Thử việc từ',
            'Thử việc tới',
            'Kết quả thử việc',
        ];
    }
}
