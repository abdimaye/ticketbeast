<?php

use App\Order;
use App\Ticket;
use App\Concert;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewOrderTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	public function user_can_view_their_order_confirmation()
	{
		// create a concert
		// create an order
		// create some tickets

		$this->disableExceptionHandling();

		$concert = factory(Concert::class)->create();

		$order = factory(Order::class)->create([
			'confirmation_number' => 'ORDERCONFIRMATION123',
			'card_last_four' => 1881,
			'amount' => 8500
		]);

		$ticketA = factory(Ticket::class)->create([
			'concert_id' => $concert->id,
			'order_id' => $order->id,
			'code' => 'TICKETCODE123'
		]);

		$ticketB = factory(Ticket::class)->create([
			'concert_id' => $concert->id,
			'order_id' => $order->id,
			'code' => 'TICKETCODE456'
		]);

		// visit the order confirmation page
		$response = $this->get("orders/ORDERCONFIRMATION123");

		$response->assertStatus(200);

		// assert we see the correct order details 
		$response->assertViewHas('order', function($viewOrder) use ($order) {
			return $order->id === $viewOrder->id;
		});

		$response->assertSee('ORDERCONFIRMATION123');
		$response->assertSee('**** **** **** 1881');
		$response->assertSee('$85.00');
		$response->assertSee('TICKETCODE123');
		$response->assertSee('TICKETCODE456');
	}

}
