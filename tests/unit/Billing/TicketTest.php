
<?php

use App\Concert;
use App\Ticket;
use Carbon\Carbon;
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

    /** 
     * @test 
     * 
	 * This test is somewhat redundant as it is covered by OrderTest@tickets_are_released_when_an_order_is_cancelled
     */
    function a_ticket_can_be_released()
    {
        $ticket = factory(Ticket::class)->states('reserved')->create();

        $this->assertNotNull($ticket->reserved_at);

        $ticket->release();

        $this->assertNull($ticket->reserved_at);
    }
}