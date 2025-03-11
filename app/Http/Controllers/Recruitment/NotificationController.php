<?php

namespace App\Http\Controllers\Recruitment;

use App\Common\Traits\ApiResponses;
use App\Models\Notifications;
use App\Repositories\Recruitment\NotificationRepository;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Recruitment\AccountRepository;
use App\Enums\RecruitmentChangeEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    use ApiResponses;
    public function __construct(AccountRepository $accountRepo, NotificationRepository $notificationRepo)
    {
        $this->notificationRepo = $notificationRepo;
        $this->accountRepo = $accountRepo;
    }

    public function index()
    {
        $datas = Notifications::whereJsonContains('user_ids', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        // Phân loại thông báo đã đọc và chưa đọc
        foreach ($datas as $notification) {
            $read_ats = json_decode($notification->read_ats, true);
            $notification->isRead = isset($read_ats) && array_key_exists(Auth::user()->id, $read_ats);
        }
        return view('recruitment.notification.index', compact('datas'));
    }

    public function markNotificationAsRead($notificationId)
    {
        // Tìm thông báo bằng ID
        $notification = Notifications::findOrFail($notificationId);

        // Kiểm tra xem người dùng hiện tại có trong danh sách người nhận thông báo không
        if (in_array(Auth::user()->id, json_decode($notification->user_ids, true))) {
            // Cập nhật trường read_ats với thời gian hiện tại cho người dùng này
            $read_ats = json_decode($notification->read_ats, true) ?? [];
            $read_ats[Auth::user()->id] = now()->toDateTimeString();
            $notification->read_ats = json_encode($read_ats);
            $notification->save();
        }
        // Chuyển hướng người dùng đến liên kết trong thông báo hoặc bất cứ đâu bạn muốn
        return redirect()->route('recruitment.notification.index');
    }

    public function markNotificationAsReadAll()
    {
        // Tìm thông báo bằng ID
        $notifications = Notifications::whereJsonContains('user_ids', (string) Auth::user()->id)->get();

        foreach ($notifications as $notification) {
            // Cập nhật trường read_ats với thời gian hiện tại cho người dùng này
            $read_ats = json_decode($notification->read_ats, true) ?? [];
            $read_ats[Auth::user()->id] = now()->toDateTimeString();
            $notification->read_ats = json_encode($read_ats);
            $notification->save();
        }
        return redirect()->route('recruitment.notification.index');
    }

    public function getNotification()
    {
        $notifications = Notifications::whereJsonContains('user_ids', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Phân loại thông báo đã đọc và chưa đọc
        foreach ($notifications as $notification) {
            $read_ats = json_decode($notification->read_ats, true);
            $notification->isRead = isset($read_ats) && array_key_exists(Auth::user()->id, $read_ats);
        }

        return $this->success($notifications);
    }

    public function getData()
    {
        $datas = $this->notificationRepo->all();
        return $this->success($datas);
    }

    public function create()
    {
        return view('recruitment/account/create');
    }

    public function store()
    {
        return view('recruitment/account/create');
    }


}
