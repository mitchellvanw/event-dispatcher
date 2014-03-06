<?php namespace Mitch\EventDispatcher;

interface Listener
{
    public function handle(Event $event);
}