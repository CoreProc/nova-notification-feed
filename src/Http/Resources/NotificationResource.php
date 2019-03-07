<?php

namespace Coreproc\NovaNotificationFeed\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->data,
            'read_at' => ! empty($this->read_at) ? $this->read_at->toDate() : null,
            'created_at' => ! empty($this->created_at) ? $this->created_at->toDate() : null,
        ];
    }
}
