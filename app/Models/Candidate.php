<?php

namespace App\Models;

use App\Models\Notifications;
use App\Notifications\CandidateStatusChanged;
use App\Repositories\Recruitment\UserRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notification;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Schema;

class Candidate extends BaseModel
{
    const ACTION_COMMENT = 1;
    const ACTION_DATABASE = 2;
    use HasUuids;
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // Chain fluent methods to set configuration options
            ->logOnly(['*'])
            ->useLogName('Ứng viên')
            ->setDescriptionForEvent(function (string $eventName) {
                return "Bảng này đã được {$eventName}";
            });
    }
    protected static $tableName = "candidate";
    protected $table = "candidate";

    protected $fillable = [
        'name',
        'is_submit',
        'can_edit',
        'form_token',
        'position_id',
        'department_id',
        'recruiter',
        'relationer',
        'received_time',
        'source_id',
        'receiver_id',
        'relationship_note',
        'gender',
        'birthday',
        'phone_number',
        'email',
        'household',
        'address',
        'address_detail',
        'level',
        'branch',
        'major',
        //Chuyên ngành học - nhập tay
        'experience',
        //Nhập tay
        'rank',
        'english',
        'training_place',
        'other_language',
        'other_software',
        'info1',
        'info2',

        'interview_comment',
        'interview_result',
        'interviewer',
        'interview_date',

        'interview_comment0',
        'interview_result0',
        'interviewer0',
        'interview_date0',

        'interview_comment1',
        'interview_result1',
        'interviewer1',
        'interview_date1',

        'interview_comment2',
        'interview_result2',
        'interviewer2',
        'interview_date2',

        'interview_comment3',
        'interview_result3',
        'interviewer3',
        'interview_date3',

        'recruitment_result',
        'user_uid',
        'probation_result',
        'now_result',

        'approve_id',
        'timeline',
        'approve',
        'status',
        'request_form_id',
        'stage',
        'created_by',
        'score',

        //Giá trị value của enum tạng thái
        'level_value',
        'rank_value',
        'branch_value',
        'gender_value',
        'interview_result_value',
        'interview_result0_value',
        'interview_result1_value',
        'interview_result2_value',
        'interview_result3_value',
        'status_value',
        'active',
        'interview_schedule_id',

        //Ngoại ngữ
        'languague',
        //Kĩ năng
        'skill',
        //Kinh nghiệm làm việc
        'working_process',
        //Quá trình đạo tạo
        'training_process',
        'height',
        'weight',
        'contact',
        'cccd',
        'cccd_date',
        //tính cách
        'character',
        //sở thích
        'interest',
        //điểm mạnh
        'strength',
        //điểm yếu
        'weakness',
        //thông tin tham khảo
        'infomation',
        //nguồn td khác
        'other_source',
        'duplicate_code',

        'exam_result',
        'exam_result_value',
        'now_result',
        'now_result_value',
        'recruitment_result_value',
        'probation_result_value',

        'probation_from',
        //Thử việc từ
        'probation_to',
        //Thử việc tới
        'un_recruitment_reason',
        //Lý do không đạt tuyển dụng (Lương, ko thích nữa)
        'un_exam_reason',
        //Lý do thi tuyển k đạt
        'un_probation_reason',
        //Lý do tái thử việc hoặc không đạt thử việc

        'salary_from',
        'salary_to',
        'salary_note',
        'reject_job_date',
    ];

    protected $casts = [
        'timeline' => 'array',
        // 'skill' => 'array',
        // 'languague' => 'array',
        // 'working_process' => 'array',
        // 'training_process' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($candidate) {
            // CandidateStatusHistory::create([
            //     'candidate_id' => $candidate->id,
            //     'old_status' => null,
            //     'new_status' => $candidate->status,
            //     'changed_at' => now(),
            // ]);
        });

        static::updating(function ($candidate) {
            if ($candidate->isDirty('status')) {
                // CandidateStatusHistory::create([
                //     'candidate_id' => $candidate->id,
                //     'old_status' => $candidate->getOriginal('status'),
                //     'new_status' => $candidate->status,
                //     'changed_at' => now(),
                // ]);

                Notifications::create([
                    'type' => 'SomeType',
                    // Loại thông báo (bạn cần định nghĩa)
                    'notifiable_type' => Candidate::class,
                    'notifiable_id' => $candidate->id,
                    'push_id' => 'SomePushID',
                    // Định nghĩa theo logic của bạn
                    'user_ids' => json_encode([ /* danh sách ID của người nhận */]),
                    'data' => json_encode([
                        // Dữ liệu bạn muốn lưu
                        'message' => 'Status was updated',
                    ]),
                    'message' => 'Ứng viên: ' . $candidate->name . ' đã đổi trạng thái sang ' . $candidate->status_value,
                    'link' => '/recruitment/candidate/edit/' . $candidate->id,
                    'read_ats' => json_encode([]),
                ]);
            }
        });

    }

    public static function getRelationships()
    {
        return [
            'belongsTo' => [
                'position',
            ],
        ];
    }

    public static function getTableColumnsWithRelations()
    {
        $tableName = self::$tableName;

        // Lấy cột từ bảng chính (candidates)
        $candidateColumns = Schema::getColumnListing($tableName);

        // Lấy danh sách quan hệ của model Candidate
        $relations = self::getRelationships();
        dd($relations);
        // Lặp qua danh sách quan hệ và lấy cột từ các bảng liên quan
        // $relatedColumns = [];
        // foreach ($relations as $relationName => $relation) {
        //     $relatedModel = $relation->getRelated();
        //     $relatedTable = $relatedModel->getTable();
        //     $relatedColumns = array_merge($relatedColumns, Schema::getColumnListing($relatedTable));
        // }

        // Kết hợp cột từ bảng chính và các bảng liên quan
        // $allColumns = array_merge($candidateColumns, $relatedColumns);

        return;
    }

    private static function getColumnsFromRelation($relation)
    {
        $relatedModel = (new self)->$relation()->getRelated();
        $columns = Schema::getColumnListing($relatedModel->getTable());

        $prefixedColumns = [];
        foreach ($columns as $column) {
            $prefixedColumns[] = "{$relation}.$column";
        }

        return $prefixedColumns;
    }

    public static function getTableColumns()
    {
        $datas = Schema::getColumnListing(self::$tableName);

        // Danh sách tất cả các relationships
        $relations = ['department', 'position', 'interview_schedules'];

        // Lấy tất cả các cột từ từng relationship và thêm vào mảng $datas
        foreach ($relations as $relation) {
            $datas = array_merge($datas, self::getColumnsFromRelation($relation));
        }

        return $datas;
    }


    public function getInterviewDateByStage($stage)
    {
        $columnName = 'interview_date' . $stage;

        if ($this->attributes[$columnName]) {
            return $this->attributes[$columnName];
        }
        return null;
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function interview_schedules()
    {
        return $this->hasMany(InterviewSchedule::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id', 'id');
    }
    public function source()
    {
        return $this->belongsTo(Source::class, 'source_id', 'id');
    }
    public function receive()
    {
        return $this->belongsTo(User::class, 'receiver_id', 'id');
    }
    public function household()
    {
        return $this->belongsTo(Provinces::class, 'household', 'code');
    }
    public function address()
    {
        return $this->belongsTo(Provinces::class, 'address', 'code');
    }
    public function interviewer()
    {
        return $this->belongsTo(User::class, 'interviewer', 'id');
    }
    public function interviewer1()
    {
        return $this->belongsTo(User::class, 'interviewer1', 'id');
    }
    public function interviewer2()
    {
        return $this->belongsTo(User::class, 'interviewer2', 'id');
    }
    public function interviewer3()
    {
        return $this->belongsTo(User::class, 'interviewer3', 'id');
    }
    public function interviewer0()
    {
        return $this->belongsTo(User::class, 'interviewer0', 'id');
    }
    // public function interviewSchedules()
    // {
    //     return $this->belongsToMany('App\Models\InterviewSchedule', 'candidate_interview_schedule', 'candidate_id', 'interview_schedule_id');
    // }

    public function candidateInterviewSchedules()
    {
        return $this->hasMany('App\Models\CandidateInterviewSchedule', 'candidate_id');
    }

}
