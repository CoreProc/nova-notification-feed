<?php

namespace Coreproc\NovaNotificationFeed\Notifications;

use Illuminate\Notifications\Messages\BroadcastMessage;

class NovaBroadcastMessage extends BroadcastMessage
{
    /**
     * Create a new message instance.
     *
     * @param array $data
     * @return void
     */
    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->data = [
            'data' => $data,
            'read_at' => null,
            'created_at' => now()->toDate(),
        ];
    }
}
