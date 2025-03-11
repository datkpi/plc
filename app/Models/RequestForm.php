<?php

namespace App\Models;

use App\Common\Traits\EnumHandle;
use App\Enums\RequestFormEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RequestForm extends BaseModel
{
    use HasUuids, Notifiable;
    use LogsActivity;
    use EnumHandle;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // Chain fluent methods to set configuration options
            ->logOnly(['*'])
            ->useLogName('Phiếu tuyển dụng')
            ->setDescriptionForEvent(function (string $eventName) {
                return "Bảng này đã được {$eventName}";
            });
    }

    protected $table = "request_form";
    const APPROVE_1 = 1;
    const APPROVE_2 = 2;
    const APPROVE_3 = 3;
    const APPROVE_4 = 4;
    const APPROVE_SUCCESS = 5;
    const APPROVE = 'approve'; //duyệt hay từ chối
    const REJECT = 'reject';
    protected $fillable = [
        'name',
        'department_id',
        //Tích chọn, đơn vị ứng tuyển
        'request_date',
        'approve_id',
        'recruitment_plan_id',
        'position_id',
        'recruited',
        'quantity',
        'staffing',
        'manager_id',
        //cán bộ phụ trách trực tiếp
        'staff_quantity',
        //Số lượng nhân viên đã có
        'estimate_start',
        //Thời gian dự kiến bắt đầu tuyển
        'estimate_end',
        //Thời gian dự kiến bắt đầu tuyển
        'status',
        'job_description',
        //Mô tả công việc
        'recruitment_type',
        'recruitment_change',
        'address_detail',
        'level',
        'other_level',
        'field',
        'other_field',
        'experience',
        'other_experience',
        'language_level',
        'language_name',
        'other_language',
        'changer_name',
        'changer_position',
        'changer_position_id',
        'work_last_time',
        'new_recruitment_reason',
        'file',
        'approve_status',
        'budget',
        're    cruitment_method',
        'note',
        'stage',
        'created_by',
        'approve_deadline',
        'list_approve',
        'current_approve',
        'position',
        'salary_from',
        'salary_to',
        'annual_employee_id',
        'sub', //Giảm trừ
        'sub_reason',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($requestForm) {
            $oldQuantity = Position::where('id', $requestForm->position_id)->value('recruiting');

            // Cập nhật giá trị quantity trong model Position
            Position::where('id', $requestForm->position_id)->update([
                'recruiting' => $oldQuantity + $requestForm->quantity
            ]);
            $approveList = Approve::where('department_id', $requestForm->department_id)->first();
            if ($approveList) {
                $user_ids = $requestForm->created_by . ',' . $approveList->approve_1 . ',' . $approveList->approve_2 . ',' . $approveList->approve_3 . ',' . $approveList->approve_4;
                $user_ids = explode(',', $user_ids);
                Notifications::create([
                    'type' => 'Database',
                    'notifiable_type' => RequestForm::class,
                    'notifiable_id' => $requestForm->id,
                    'push_id' => 'SomePushID',
                    'user_ids' => json_encode($user_ids),
                    'data' => json_encode([
                        'title' => 'Phiếu đề xuất tuyển dụng',
                        'message' => Auth::user()->name . ' đã thêm phiếu yêu cầu tuyển dụng: ' . $requestForm->name,
                    ]),
                    'link' => '/recruitment/request-from/edit/' . $requestForm->id,
                    'read_ats' => json_encode([]),
                ]);
            }

            $user_ids = $requestForm->current_approve;
            $user_ids = [$user_ids];
            Notifications::create([
                'type' => 'Database',
                'notifiable_type' => RequestForm::class,
                'notifiable_id' => $requestForm->id,
                'push_id' => 'SomePushID',
                'user_ids' => json_encode($user_ids),
                'data' => json_encode([
                    'title' => 'Duyệt phiếu đề xuất tuyển dụng',
                    'message' => 'Bạn có yêu cầu duyệt từ phiếu tuyển dụng ' . $requestForm->name,
                ]),
                'link' => '/recruitment/request-from/edit/' . $requestForm->id,
                'read_ats' => json_encode([]),
            ]);
        });

        static::updated(function ($requestForm) {
            if ($requestForm->isDirty('status')) {
                $approveList = Approve::where('department_id', $requestForm->department_id)->first();
                if ($approveList) {
                    $user_ids = $requestForm->created_by . ',' . $approveList->approve_1 . ',' . $approveList->approve_2 . ',' . $approveList->approve_3 . ',' . $approveList->approve_4;
                    $user_ids = explode(',', $user_ids);
                    $obj = new self();
                    $statusValue = $obj->getValueInEnum(RequestFormEnum::cases(), $requestForm->status);
                    Notifications::create([
                        'type' => 'Database',
                        'notifiable_type' => RequestForm::class,
                        'notifiable_id' => $requestForm->id,
                        'push_id' => 'SomePushID',
                        'user_ids' => json_encode($user_ids),
                        'data' => json_encode([
                            'title' => 'Phiếu đề xuất tuyển dụng',
                            'message' => 'Phiếu đề xuất: ' . $requestForm->name . ' đã đổi sang trạng thái ' . $statusValue,
                        ]),
                        'link' => '/recruitment/request-from/edit/' . $requestForm->id,
                        'read_ats' => json_encode([]),
                    ]);
                }
            }



            if ($requestForm->isDirty('current_approve')) {
                $user_ids = $requestForm->current_approve;
                $user_ids = [$user_ids];
                Notifications::create([
                    'type' => 'Database',
                    'notifiable_type' => RequestForm::class,
                    'notifiable_id' => $requestForm->id,
                    'push_id' => 'SomePushID',
                    'user_ids' => json_encode($user_ids),
                    'data' => json_encode([
                        'title' => 'Duyệt phiếu đề xuất tuyển dụng',
                        'message' => 'Bạn có yêu cầu duyệt từ phiếu tuyển dụng ' . $requestForm->name,
                    ]),
                    'link' => '/recruitment/request-from/edit/' . $requestForm->id,
                    'read_ats' => json_encode([]),
                ]);
            }
        });
    }

    /**
     * Get all of the comments for the RequestForm
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function requestFormDetails()
    {
        return $this->hasMany(RequestFormDetail::class, 'request_form_id', 'id');
    }

    /**
     * Get the user that owns the RequestForm
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id', 'id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

}
