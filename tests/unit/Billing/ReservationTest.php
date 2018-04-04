<?php

use App\Concert;
use App\Ticket;
use App\Reservation;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReservationTest extends TestCase
{
    // use DatabaseMigrations;

    /** @test */
    function calculating_the_total_cost()
    {
        $tickets = collect([
        	(object)['price' => 1200],
        	(object)['price' => 1200],
        	(object)['price' => 1200]
        ]);

        $reservation = new Reservation($tickets);

        $this->assertEquals(3600, $reservation->totalCost());
    }

    /** @test */
    function retrieving_the_reservations_tickets()
    {
        $tickets = collect([
            (object)['price' => 1200],
            (object)['price' => 1200],
            (object)['price' => 1200]
        ]);

        $reservation = new Reservation($tickets);

        $this->assertEquals($tickets, $reservation->tickets());
    }

    /** @test */
    function reserved_tickets_are_released_when_a_reservation_is_cancelled()
    {
        // $ticket1 = Mockery::mock(Ticket::class);
        // $ticket1->shouldReceive('release')->once();
        
        // $ticket2 = Mockery::mock(Ticket::class);
        // $ticket2->shouldReceive('release')->once();

        // $ticket3 = Mockery::mock(Ticket::class);
        // $ticket3->shouldReceive('release')->once();

        $tickets = collect([
            // a spy is similar to a mock but the assertions are done afterwards
            Mockery::spy(Ticket::class),
            Mockery::spy(Ticket::class),
            Mockery::spy(Ticket::class),
        ]);

        $reservation = new Reservation($tickets);

        $reservation->cancel();

        foreach($tickets as $ticket) {
            // notice the assertion for a spy is shoudHaveReceived (past tense)
            // instead of a mocks shouldReceive (future tense)
            $ticket->shouldHaveReceived('release');
        }
    }
}