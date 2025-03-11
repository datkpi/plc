<?php

namespace App\Common\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Common\StringHelpers;
use App\Enums\LevelEnum;
use App\Enums\RecruitmentChangeEnum;
use App\Enums\ActionEnum;
use App\Enums\BranchEnum;
use App\Enums\ApproveEnum;
use App\Enums\ExperienceEnum;
use App\Enums\FieldEnum;
use App\Enums\GenderEnum;
use App\Enums\LanguageLevelEnum;
use App\Enums\MailStatusEnum;
use App\Enums\InterviewResultEnum;
use App\Enums\NowResultEnum;
use App\Enums\ProbationResultEnum;
use App\Enums\RankEnum;
use App\Enums\RecruitmentMethodEnum;
use App\Enums\RecruitmentResultEnum;
use App\Enums\RecruitmentTypeEnum;

trait GetEnumDatas
{
    public function success(mixed $data, int $statusCode = Response::HTTP_OK): JsonResponse
    {
        return new JsonResponse($data, $statusCode);
    }
}