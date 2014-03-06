<?php 

require_once('stubs/TestUserAddedEvent.php');

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

    public function testCanDispatchEventToListener()
    {
        $event = new TestUserAddedEvent;

        $listener = m::mock('Mitch\EventDispatcher\Listener');
        $listener->shouldReceive('handle')->with($event)->andReturn(null)->once();

        $this->sut->listenOn('accounts.user_added', $listener);

        $this->sut->dispatch($event);
    }
} 
