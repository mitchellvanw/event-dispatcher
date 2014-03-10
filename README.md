# Event Dispatcher

An Event Dispatcher built with a focus on Domain Events.

## Installation

Begin by installing the package through Composer. Edit your project's `composer.json` file to require `mitch/event-dispatcher`.

  ```php
  "require": {
    "mitch/event-dispatcher": "0.1.x"
  }
  ```

Next use Composer to update your project from the the Terminal:

  ```php
  php composer.phar update
  ```

Once the package has been installed you'll need to add the service provider. Open your `app/config/app.php` configuration file, and add a new item to the `providers` array.

  ```php
  'Mitch\EventDispatcher\Laravel\EventDispatcherServiceProvider'
  ```

## How It Works

### Event
In your domain you'll create an event, for let's say when a new user has been added.
Lets call this event `UserAddedEvent`. This event will hold the necessary information for the listener to fulfill it's job.
You have complete freedom about which arguments it takes, since you'll be the one passing them in.
In some ways this event is a `Date Transfer Object` (DTO).

For example:

```php
<?php namespace Domain\Accounts\Events;

use Mitch\EventDispatcher\Event;

class UserAddedEvent implements Event
{
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function getName()
    {
        return 'accounts.user_added';
    }
}
```

### Listener
An event without a listener does no good for us, so lets create an email listener `UserAddedMailerListener` for the event `UserAddedEvent`.

```php
<?php namespace Domain\Accounts\Listeners;

use Mitch\EventDispatcher\Listener;

class UserAddedMailerListener implements Listener
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
$listener = new UserAddedMailerListener($mailer);

$dispatcher = new Dispatcher;
$dispatcher->addListener('accounts.user_added', $listener);

// Dispatching event
$user = // A wild user appeared..
$event = new UserAddedEvent($user);

$dispatcher->dispatch($event);
```

For extra hipster points you can dispatch multiple events in 1 call.

```php
$user = ...;
$achievement = ...;
$events = [
    new UserAddedEvent($user),
    new UserGotAchievementEvent($user, $achievement)
];

$dispatcher->dispatch($events);
```

## That's it!
