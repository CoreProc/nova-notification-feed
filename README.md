# Nova Notification Feed

[![Latest Version on Packagist](https://img.shields.io/packagist/v/coreproc/nova-notification-feed.svg?style=flat-square)](https://packagist.org/packages/coreproc/nova-notification-feed)
[![Quality Score](https://img.shields.io/scrutinizer/g/coreproc/nova-notification-feed.svg?style=flat-square)](https://scrutinizer-ci.com/g/coreproc/nova-notification-feed)
[![Total Downloads](https://img.shields.io/packagist/dt/coreproc/nova-notification-feed.svg?style=flat-square)](https://packagist.org/packages/coreproc/nova-notification-feed)

A Laravel Nova package that adds a notification feed in your Nova app and uses [Pusher](https://pusher.com) to receive and broadcast notifications.

![](nova-notification-feed.gif)

## Installation

You can install the package in to a Laravel app that uses [Nova](https://nova.laravel.com) via composer:

```bash
composer require coreproc/nova-notification-feed
```

This package makes use of Laravel's database notification feature and [Pusher](https://pusher.com) to receive and broadcast notifications.

Make sure that you already have a Pusher project set up and prepare your Laravel project to use it for broadcasting notifications.

Run the following to get the `notifications` table if you have not done so already:

```
php artisan notifications:table

php artisan migrate
```

Before broadcasting any events, you will first need to register the `App\Providers\BroadcastServiceProvider`. In fresh Laravel applications, you only need to uncomment this provider in the `providers` array of your `config/app.php` configuration file. This provider will allow you to register the broadcast authorization routes and callbacks.

Make sure that you configure the correct environment variables in your `.env` file:

```
BROADCAST_DRIVER=pusher

PUSHER_APP_ID=xxxxxxx
PUSHER_APP_KEY=xxxxxxx
PUSHER_APP_SECRET=xxxxxx
PUSHER_APP_CLUSTER=xxx
```

You will also need to ensure that you have added an authorization broadcast route in `routes/channels.php`:

```php
Broadcast::channel('users.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});
```

The channel name will depend on your `User` model having the `Notifiable` trait. You can override the `receivesBroadcastNotificationsOn` to use a different channel name.

```php
class User extends Authenticatable
{
    use Notifiable;
    
    ...
    
    /**
     * The channels the user receives notification broadcasts on.
     *
     * @return string
     */
    public function receivesBroadcastNotificationsOn()
    {
        return 'users.' . $this->id;
    }
}
```

Finally, once you have ensure that this is set up, you will also need to override Nova's `layout.blade.php`. Create a layout file in `resources/views/vendor/nova/layout.blade.php` and copy the contents from `vendor/laravel/nova/resources/views/layout.blade.php`.

```
cp vendor/laravel/nova/resources/views/layout.blade.php resources/views/vendor/nova/layout.blade.php
```

Add the following Vue component to the layout template right after the user dropdown section.

```
...
<dropdown class="ml-auto h-9 flex items-center dropdown-right">
  @include('nova::partials.user')
</dropdown>

@include('nova_notification_feed::notification_feed')
...
```

You should now be able to see the notification bell on the top right of your Nova UI.

## Usage

To broadcast notifications to your notification feed in the Nova app, you can make a notification class that sends notifications via `database` and `broadcast`. Here is an example notification class:

```php
use Coreproc\NovaNotificationFeed\Notifications\NovaBroadcastMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class TestNotification extends Notification
{
    use Queueable;

    protected $level = 'info';
    protected $message = '';

    /**
     * Create a new notification instance.
     *
     * @param $level
     * @param $message
     */
    public function __construct($level, $message = 'Test message')
    {
        $this->level = $level;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [
            'database',
            'broadcast',
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'level' => $this->level,
            'message' => $this->message,
            'url' => 'https://coreproc.com',
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new NovaBroadcastMessage($this->toArray($notifiable));
    }
}
```

Nova Notification Feed relies on having three variables passed in the `data` field of the notification entry: `level`, `message`, and `url`.

Additionally, you can use the `NovaBroadcastMessage` class in the `toBroacast()` method to ensure that the format of the broadcast can be read by the frontend.

## Roadmap

- Differentiate background color of a new notification
- Support other drivers aside from Pusher
- Better design?

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email chris.bautista@coreproc.ph instead of using the issue tracker.

## Credits

- [Chris Bautista](https://github.com/chrisbjr)

## About CoreProc

CoreProc is a software development company that provides software development services to startups, digital/ad agencies, and enterprises.

Learn more about us on our [website](https://coreproc.com).
 
## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
