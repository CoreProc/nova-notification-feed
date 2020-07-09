<?php

namespace Coreproc\NovaNotificationFeed\Http\Controllers;

use Coreproc\NovaNotificationFeed\Http\Resources\NotificationCollection;

class NotificationsController
{
    public function index()
    {
        $notifications = request()->user()->notifications()
            ->orderByDesc('created_at');

        $only = config('nova_notifications.only', []);

        if (! empty($only)) {
            $notifications->whereIn('type', $only);
        }

        if (request()->get('mark_as_read', false)) {
            // Mark notifications as read
            request()->user()->unreadNotifications->markAsRead();
        }

        $userBrands = request()->user()->getBrands()->pluck('id');
        $notifications->where(function ($builder) use ($userBrands) {
            $builder->whereIn('brand_id', $userBrands);
            $builder->orWhere('brand_id', '=', null);
        });

        return new NotificationCollection($notifications->paginate());
    }
}
