<?php

namespace Coreproc\NovaNotificationFeed\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class NotificationCollection extends ResourceCollection
{
    public $collects = NotificationResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
        ];
    }

    public function with($request)
    {
        return [
            'meta' => [
                'unread_count' => request()->user()->notifications()
                    ->whereNull('read_at')
                    ->count(),
            ],
        ];
    }
}
