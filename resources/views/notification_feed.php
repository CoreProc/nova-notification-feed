<notification-feed pusher-key="{{ env('PUSHER_APP_KEY') }}"
                    pusher-cluster="{{ env('PUSHER_APP_CLUSTER') }}"
                    broadcast-on="{{ request()->user()->receivesBroadcastNotificationsOn() }}">
</notification-feed>
