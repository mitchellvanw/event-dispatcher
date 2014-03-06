# Event Dispatcher

This Event Dispatcher has been built with a focus on Domain Events.

## How It Works

### Event
In your domain you'll create an event, for let's say when a new user has been created.
Lets call this event `UserCreatedEvent`. This event will hold the necessary information for the listener to fulfill it's job.
You have complete freedom about which arguments it takes, since you'll be the one passing them in.
In some ways this event is a `Date Transfer Object` (DTO).

For example:

```php
<?php namespace Domain\Accounts\Events;

use Mitch\EventDispatcher\Event;

class UserCreatedEvent implements Event
{
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function getName()
    {
        return 'accounts.user_created';
    }
}
```

### Listener
An event without a listener does no good for us, so lets create an email listener `UserCreatedMailerListener` for the event `UserCreatedEvent`.

```php
<?php namespace Domain\Accounts\Listeners;

use Mitch\EventDispatcher\Listener;

class UserCreatedMailerListener implements Listener
{
    private $mailer

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function handle(Event $event)
    {
        // Do something with the event
    }
}
```

Same rule with the listeners as the events, you have complete freedom with the arguments.
When an event is dispatched the `handle` method on the correct listeners will be called.

### Listening
Now we got the building blocks ready lets start listening for some new users, shall we.
For the sake of this example, the code is kept as simple as possible.

```php
// Listening for event
$mailer = // Some mail package...
$listener = new UserCreatedMailerListener($mailer);

$dispatcher = new Dispatcher;
$dispatcher->listenOn('accounts.user_created', $listener);
// The listenOn() method has a third optional parameter for possible priority.


// Dispatching event
$user = // A wild user appeared..
$event = new UserCreatedEvent($user);

$dispatcher->dispatch($event);
```

For extra hipster points you can dispatch multiple events in 1 call.

```php
$user = ...;
$achievement = ...;
$events = [
    new UserCreatedEvent($user),
    new UserGotAchievementEvent($user, $achievement)
];

$dispatcher->dispatch($events);
```

## That's it!
