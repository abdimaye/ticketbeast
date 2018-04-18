<?php

use App\Concert;
use App\Order;
use App\Reservation;
use App\Ticket;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

	/** @test */
	public function test_retrieving_order_by_confirmation_number()
	{
		$order = factory(Order::class)->create([
			'confirmation_number' => 'ORDERCONFIRMATION1234'
		]);

		$foundOrder = Order::findByConfirmationNumber('ORDERCONFIRMATION1234');

		$this->assertEquals($order->id, $foundOrder->id);
	}

	/** @test */
	public function retrieving_a_non_existent_order_by_confirmation_throws_an_exception()
	{
		try {
			Order::findByConfirmationNumber('ORDERCONFIRMATION1234');
		} catch (ModelNotFoundException $e) {
			return;
		}

		$this->fail('No matching model was found for for the specified order number, but an exception was not thrown');
	}

}