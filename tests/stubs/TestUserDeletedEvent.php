<?php

use Mitch\EventDispatcher\Event;

class TestUserDeletedEvent implements Event
{
    public function getName()
    {
        return 'accounts.user_deleted';
    }
}
