<?php

use App\Concert;
use App\Order;
use App\Reservation;
use App\Ticket;
use App\Billing\Charge;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	function creating_an_order_from_tickets_email_and_charge()
	{
	    $tickets = factory(Ticket::class, 3)->create();
	    $charge = new Charge(['amount' => 6000, 'card_last_four' => '1234']);

	    $order = Order::forTickets($tickets, 'john@example.com', $charge);
	    
	    $this->assertEquals('john@example.com', $order->email);
	    $this->assertEquals(3, $order->ticketQuantity());
	    $this->assertEquals(6000, $order->amount);
	    $this->assertEquals('1234', $order->card_last_four);
	}

	/** @test */
	function converting_to_an_array()
	{
	    $order = factory(Order::class)->create([
	    	'confirmation_number' => 'ORDERCONFIRMATION1234',
	    	'email' => 'jane@example.com',
	    	'amount' => 6000
	    ]);

	    $order->tickets()->saveMany(factory(Ticket::class)->times(5)->create());
	    
	    $result = $order->toArray();

	    $this->assertEquals([
	    	'confirmation_number' => 'ORDERCONFIRMATION1234',
	    	'email' => 'jane@example.com',
	    	'ticket_quantity' => 5,
	    	'amount' => 6000
	    ], $result);
	}

	/** @test */
	public function retrieving_order_by_confirmation_number()
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