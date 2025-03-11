<?php

namespace App\Enums;

enum ScheduleMailEnum: string
{
    case thu_moi_ung_tuyen = 'Mẫu thư mời ứng tuyển';
    case thu_moi_phong_van = 'Mẫu thư mời phỏng vấn';
    case thu_cam_on_phong_van = 'Mẫu thư cám ơn phỏng vấn';
    case thu_ket_qua_phong_van = 'Mẫu thư kết quả phỏng vấn';
    case thu_moi_nhan_viec = 'Mẫu thư mời nhận việc';
    case thu_cam_on_ung_tuyen = 'Mẫu thư cám ơn ứng tuyển';
}
