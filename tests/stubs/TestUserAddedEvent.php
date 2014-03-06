<?php

use Mitch\EventDispatcher\Event;

class TestUserAddedEvent implements Event
{
    public function getName()
    {
        return 'accounts.user_added';
    }
}
