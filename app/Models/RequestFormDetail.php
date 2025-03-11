<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RequestFormDetail extends BaseModel
{
    use HasUuids;
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // Chain fluent methods to set configuration options
            ->logOnly(['*'])
            ->useLogName('Chi tiết phiếu tuyển dụng')
            ->setDescriptionForEvent(function (string $eventName) {
                return "Bảng này đã được {$eventName}";
            });
    }
    protected $table = "request_form_detail";
    const ACTION_COMMENT = 1;
    const ACTION_DATABASE = 2;
    const ACTION_APPROVE = 3;
    protected $fillable = [
        'request_form_id',
        'is_approve',
        'action',
        'comment',
        'content',
        'file',
        'tag_user',
        'is_send_mail',
        'created_by',
    ];

    /**
     * Summary of createdDateTime
     * @return string
     */
    public function createdDateTime()
    {
        return date('d/m/Y H:i:s', strtotime($this->created_at));
    }


    /**
     * Get the user that owns the RequestFormDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function requestForm()
    {
        return $this->belongsTo(RequestForm::class, 'request_form_id', 'id');
    }

}