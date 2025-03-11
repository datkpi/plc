<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class InterviewSchedule extends BaseModel
{
    use HasUuids;
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // Chain fluent methods to set configuration options
            ->logOnly(['*'])
            ->useLogName('Lịch phỏng vấn')
            ->setDescriptionForEvent(function (string $eventName) {
                return "Bảng này đã được {$eventName}";
            });
    }
    protected $table = "interview_schedule";
    protected $fillable = [
        'request_form_id',
        'interviewer',
        'relationer',
        'interview_from',
        'interview_to',
        'created_by',
        'name',
        'count_candidate',
        'address',
        'candidate_id',
        'stage',
        'stage_success',
        'status',
        'interview_comment',
        'can_evaluate',
        'react_at',
        //Người có quyền nhận xét
        'final_evaluate',
        //Người đánh giá cuối cùng
        'is_interviewed',
        //Đã phỏng vấn chưa
        'is_send_mail_interviewed',
        //Đã gửi mail cám ơn chưa
        'is_send_mail_interviewing',
        //Đã gửi mail mời phỏng vấn chưanhm
    ];

    protected $casts = [
        'interviewer' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($interviewSchedule) {
            $interviewer = $interviewSchedule->can_evaluate;
            $relationer = $interviewSchedule->relationer;
            $user_ids = $interviewer . ',' . $relationer;
            $user_ids = explode(',', $user_ids);
            Notifications::create([
                'type' => 'Database',
                'notifiable_type' => InterviewSchedule::class,
                'notifiable_id' => $interviewSchedule->id,
                'push_id' => 'SomePushID',
                'user_ids' => json_encode($user_ids),
                'data' => json_encode([
                    'title' => 'Lịch phỏng vấn',
                    'message' => 'Bạn đã được thêm vào một lịch phỏng vấn',
                ]),
                'link' => '/recruitment/interview-schedule/edit/' . $interviewSchedule->id,
                'read_ats' => json_encode([]),
            ]);
        });

        static::updating(function ($interviewSchedule) {
            if ($interviewSchedule->isDirty('interview_from') || $interviewSchedule->isDirty('interview_to')) {
                $interviewer = $interviewSchedule->can_evaluate;
                $relationer = $interviewSchedule->relationer;
                $user_ids = $interviewer . ',' . $relationer . $interviewSchedule->created_by;
                $user_ids = explode(',', $user_ids);
                Notifications::create([
                    'type' => 'Database',
                    'notifiable_type' => InterviewSchedule::class,
                    'notifiable_id' => $interviewSchedule->id,
                    'push_id' => 'SomePushID',
                    'user_ids' => json_encode($user_ids),
                    'data' => json_encode([
                        'title' => 'Cập nhật lịch phỏng vấn',
                        'message' => 'Lịch phỏng vấn: ' . $interviewSchedule->name . ' đổi từ ' . date('d-m-Y H:i:s', strtotime($interviewSchedule->getOriginal('interview_from'))) . ' - ' . date('d-m-Y H:i:s', strtotime($interviewSchedule->getOriginal('interview_to'))) . ' sang ' . date('d-m-Y H:i:s', strtotime($interviewSchedule->interview_from)) . ' - ' . date('d-m-Y H:i:s', strtotime($interviewSchedule->interview_to)),
                    ]),
                    'link' => '/recruitment/interview-schedule/edit/' . $interviewSchedule->id,
                    'read_ats' => json_encode([]),
                ]);
            }
        });

    }

    public function interviewAddress()
    {
        return $this->belongsTo(InterviewAddress::class, 'address', 'id');
    }

    public function requestForm()
    {
        return $this->belongsTo(RequestForm::class, 'request_form_id');
    }

    public function candidates()
    {
        return $this->hasMany(Candidate::class, 'interview_schedule_id', 'id');
    }

}
