<?php namespace Mitch\EventDispatcher;

class Dispatcher
{
    private $listeners = [];
    private $sorted = [];

    public function dispatch($events)
    {
        foreach ((array) $events as $event) {
            $this->fireEvent($event);
        }
    }

    public function listenOn($name, Listener $listener, $priority = 0)
    {
        $this->addListener($name, $listener, $priority);
    }

    private function fireEvent(Event $event)
    {
        $name = $event->getName();
        foreach ($this->getListeners($name) as $listener) {
            call_user_func([$listener, 'handle'], $event);
        }
    }

    private function getListeners($name)
    {
        if ( ! isset($this->sorted[$name]) {
            $this->sortListeners($name);
        }
        return $this->sorted[$name];
    }

    private function sortListeners($name)
    {
        $this->sorted[$name] = [];
        if (isset($this->listeners[$name]) {
            $sorted = $this->listeners[$name];
            krsort($sorted);
            $this->sorted[$name] = $sorted;
        }
    }

    private function addListener(Listener $listener)
    {
        $this->listeners[$name][$priority][] = $listener;
        $this->clearSorted($name);
    }

    private function clearSorted($name)
    {
        unset($this->sorted[$name]);
    }
}