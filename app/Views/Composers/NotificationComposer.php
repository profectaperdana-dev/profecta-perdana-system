<?php

namespace App\View\Composers;

use App\Models\NotificationsModel;
use Illuminate\View\View;

class NotificationComposer
{
    protected $notification;

    public function __construct()
    {
        $notif = NotificationsModel::where('status', '0')
            ->where('role_id', @auth()->user()->role_id)
            ->latest()
            ->limit(4)
            ->get();
        // Dependencies are automatically resolved by the service container...
        $this->notification = $notif;
    }

    public function compose(View $view)
    {
        return $view->with('notif', $this->notification);
    }
}
