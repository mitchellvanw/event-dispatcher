<?php namespace Mitch\EventDispatcher;

class Dispatcher
{
    private $listeners = [];

    public function dispatch($events)
    {
        if ( ! is_array($events)) {
            return $this->fireEvent($events);
        }

        foreach ($events as $event) {
            $this->fireEvent($event);
        }
    }

    public function listenOn($name, Listener $listener)
    {
        $this->addListener($name, $listener);
    }

    private function fireEvent(Event $event)
    {
        $listeners = $this->getListeners($event->getName());

        if ( ! $listeners) {
            return;
        }

        foreach ($this->getListeners($event->getName()) as $listener) {
            $listener->handle($event);
        }
    }

    private function getListeners($name)
    {
        if ( ! isset($this->listeners[$name])) {
            return;
        }

        return $this->listeners[$name];
    }

    private function addListener($name, Listener $listener)
    {
        $this->listeners[$name][] = $listener;
    }
}
