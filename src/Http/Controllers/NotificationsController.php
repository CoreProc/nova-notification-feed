<?php

namespace Coreproc\NovaNotificationFeed\Http\Controllers;

use Coreproc\NovaNotificationFeed\Http\Resources\NotificationCollection;
use App\Team;

class NotificationsController
{
    public function index()
    {
	$team = Team::where('id', request()->user()->current_team_id)->first();
	$notifications = $team->notifications()->orderByDesc('created_at');
        /* $notifications = request()->user()->notifications()
            ->orderByDesc('created_at');
        */
        $only = config('nova_notifications.only', []);

        if (! empty($only)) {
            $notifications->whereIn('type', $only);
        }

        if (request()->get('mark_as_read', false)) {
            // Mark notifications as read
            // request()->user()->unreadNotifications->markAsRead();
            $team->unreadNotifications->markAsRead();
        }

        return new NotificationCollection($notifications->paginate());
    }
}
