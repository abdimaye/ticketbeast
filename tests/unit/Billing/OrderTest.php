<?php

use App\Concert;
use App\Order;
use App\Reservation;
use App\Ticket;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	function creating_an_order_from_tickets_email_and_amount()
	{
	    $concert = factory(Concert::class)->create()->addTickets(5);

	    $this->assertEquals(5, $concert->ticketsRemaining());

	    $order = Order::forTickets($concert->findTickets(3), 'john@example.com', 6000);
	    
	    $this->assertEquals('john@example.com', $order->email);
	    $this->assertEquals(3, $order->ticketQuantity());
	    $this->assertEquals(6000, $order->amount);
	    $this->assertEquals(2, $concert->ticketsRemaining());
	}

  	/** @test */
	public function creating_an_order_from_a_reservstion()
	{
		$concert = factory(Concert::class)->create(['ticket_price' => 1200]);
		$tickets = factory(Ticket::class, 3)->create(['concert_id' => $concert->id]);

		$reservation = new reservation($tickets, 'john@example.com');

		$order = Order::fromReservation($reservation);

		$this->assertEquals('john@example.com', $reservation->email());
		$this->assertEquals(3, $order->ticketQuantity());
		$this->assertEquals(3600, $order->amount);
	}

	/** @test */
	function converting_to_an_array()
	{
	    $concert = factory(Concert::class)->create(['ticket_price' => 1200])->addTickets(5);
	    $order = $concert->orderTickets('jane@example.com', 5);

	    $result = $order->toArray();

	    $this->assertEquals([
	    	'email' => 'jane@example.com',
	    	'ticket_quantity' => 5,
	    	'amount' => 6000
	    ], $result);
	}

}