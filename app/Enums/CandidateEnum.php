<?php

namespace App\Enums;

enum CandidateEnum: string
{
    case new = 'Ứng tuyển';
    case interview = 'SLHS';
    case interview_success = 'Đạt SLHS';
    case interview_fail = 'Không đạt SLHS';
    case interview0 = 'PVSB';
    case interview0_success = 'Đạt PVSB';
    case interview0_fail = 'Không đạt PVSB';
    case interview1 = 'PVV1';
    case interview1_success = 'Đạt PVV1';
    case interview1_fail = 'Không đạt PVV1';
    case interview2 = 'PVV2';
    case interview2_success = 'Đạt PVV2';
    case interview2_fail = 'Không đạt PVV2';
    case interview3 = 'PVV3';
    case interview3_success = 'Đạt PVV3';
    case interview3_fail = 'Không đạt PVV3';
    case exam_success = 'Đạt thi tuyển';
    case exam_fail = 'Không đạt thi tuyển';
    case salary_deal = 'Deal lương';
    case recruitment_success = 'Nhận việc';
    case recruitment_fail = 'Không nhận việc';
    case probation_success = 'Đạt thử việc';
    case probation_fail = 'Không đạt thử việc';
    case probation_long = 'Kéo dài thử việc';
    case employee = 'Nhân viên';
    case reject = 'Từ chối';

    //Trả về value của status
    public static function getOrder(string $status): int
    {
        $allStatuses = self::cases();
        foreach ($allStatuses as $index => $case) {
            if ($case->name === $status) {
                return $index;
            }
        }
        return -1; // Trả về -1 nếu trạng thái không tồn tại trong enum
    }
    // case new = ['value' => 1, 'label' => 'Ứng tuyển'];
    // case interview = ['value' => 2, 'label' => 'SLHS'];
    // case interview_success = ['value' => 3, 'label' => 'Đạt SLHS'];
    // case interview0 = ['value' => 4, 'label' => 'PVSB'];
    // case interview0_success = ['value' => 5, 'label' => 'Đạt PVSB'];
    // case interview1 = ['value' => 6, 'label' => 'PV lần 1'];
    // case interview1_success = ['value' => 7, 'label' => 'Đạt PV lần 1'];
    // case interview2 = ['value' => 8, 'label' => 'PV lần 2'];
    // case interview2_success = ['value' => 9, 'label' => 'Đạt PV lần 2'];
    // case interview3 = ['value' => 10, 'label' => 'PV lần 3'];
    // case interview3_success = ['value' => 11, 'label' => 'Đạt PV lần 3'];
    // case offer_approving = ['value' => 12, 'label' => 'Đang duyệt offer'];
    // case offer_approved = ['value' => 13, 'label' => 'Đã duyệt offer'];
    // case invite_onboard = ['value' => 14, 'label' => 'Mời onboard'];
    // case onboarding = ['value' => 15, 'label' => 'Onboard'];
    // case employee = ['value' => 16, 'label' => 'Nhân viên'];
    // case reject = ['value' => 17, 'label' => 'Từ chối'];
}
