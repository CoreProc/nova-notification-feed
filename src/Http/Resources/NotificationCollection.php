<?php

namespace Coreproc\NovaNotificationFeed\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Team;

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
        // $team = Team::where('id', request()->user()->current_team_id)->first();
        return [
            'meta' => [
                'unread_count' => request()->user()->notifications()
                                                   ->whereNull('read_at')
                                                   ->count(),
            ],
        ];
    }
}
