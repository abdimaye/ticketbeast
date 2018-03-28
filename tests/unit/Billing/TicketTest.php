
<?php

use App\Concert;
use App\Ticket;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TicketTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	function a_ticket_can_be_reserved()
	{
	    $ticket = factory(Ticket::class)->create();
	    $this->assertNull($ticket->reserved_at);

	    $ticket->reserve();

	    $this->assertNotNull($ticket->fresh()->reserved_at);
	}

    /** @test 
	* 
	* This test is somewhat redundant as it is covered by OrderTest@tickets_are_released_when_an_order_is_cancelled
    */
    function a_ticket_can_be_released()
    {
        $concert = factory(Concert::class)->create();
        $concert->addTickets(1);
        $order = $concert->orderTickets('jane@example.com', 1);
        $ticket = $order->tickets()->first();

        $this->assertEquals($order->id, $ticket->order_id);

        $ticket->release();

        // fresh() gives a new instance of ticket
        $this->assertNull($ticket->fresh()->order_id);
    }
}