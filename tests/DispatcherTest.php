<?php 

require_once('stubs/TestUserAddedEvent.php');
require_once('stubs/TestUserDeletedEvent.php');

use Mockery as m;

class DispatcherTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->sut = new Mitch\EventDispatcher\Dispatcher;
    }

    public function tearDown()
    {
        m::close();
    }

    public function testCanCreateDispatcher()
    {
        $this->assertEquals('Mitch\EventDispatcher\Dispatcher', get_class($this->sut));
    }

    public function testCanDispatchSingleEventToListener()
    {
        $event = new TestUserAddedEvent;

        $listener = m::mock('Mitch\EventDispatcher\Listener');
        $listener->shouldReceive('handle')->with($event)->once();

        $this->sut->listenOn('accounts.user_added', $listener);
        $this->sut->dispatch($event);
    }

    public function testListenersArentNotifiedOfOtherEvents()
    {
        $event = new TestUserAddedEvent;

        $listener = m::mock('Mitch\EventDispatcher\Listener');
        $listener->shouldReceive('handle')->times(0);

        $this->sut->listenOn('accounts.user_exploded', $listener);
        $this->sut->dispatch($event);
    }

    public function testCanDispatchMultipleEventsToListener()
    {
        $event1 = new TestUserAddedEvent;
        $event2 = new TestUserAddedEvent;

        $listener = m::mock('Mitch\EventDispatcher\Listener');
        $listener->shouldReceive('handle')->with($event1)->once();
        $listener->shouldReceive('handle')->with($event2)->once();

        $this->sut->listenOn('accounts.user_added', $listener);
        $this->sut->dispatch([$event1, $event2]);
    }

    public function testCanDispatchDifferentKindsOfEventsToListener()
    {
        $eventAdd = new TestUserAddedEvent;
        $eventDelete = new TestUserDeletedEvent;

        $listenerAdd = m::mock('Mitch\EventDispatcher\Listener');
        $listenerAdd->shouldReceive('handle')->with($eventAdd)->once();

        $listenerDelete = m::mock('Mitch\EventDispatcher\Listener');
        $listenerDelete->shouldReceive('handle')->with($eventDelete)->once();

        $this->sut->listenOn('accounts.user_added', $listenerAdd);
        $this->sut->listenOn('accounts.user_deleted', $listenerDelete);
        $this->sut->dispatch([$eventAdd, $eventDelete]);
    }
}
