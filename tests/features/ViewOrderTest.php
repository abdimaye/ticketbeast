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

		$concert = factory(Concert::class)->create();

		$order = factory(Order::class)->create();

		$ticket = factory(Ticket::class)->create([
			'concert_id' => $concert->id,
			'order_id' => $order->id
		]);

		// visit the order confirmation page
		$this->get("orders/{$order->id}");

		// assert we see the correct order details 
		
	}

}
