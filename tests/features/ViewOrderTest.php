<?php

use App\Order;
use App\Ticket;
use App\Concert;
use Carbon\Carbon;
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

		$concert = factory(Concert::class)->create([
            'title' => 'The Red Chord',
            'subtitle' => 'with Animosity and Lethargy',
            'date' => Carbon::parse('March 12, 2017 8:00pm'),
            'ticket_price' => 4250,
            'venue' => 'The Mosh Pit',
            'venue_address' => '123 Example Lane',
            'city' => 'Laraville',
            'state' => 'ON',
            'zip' => '17916',
        ]);

		$order = factory(Order::class)->create([
			'confirmation_number' => 'ORDERCONFIRMATION123',
			'card_last_four' => 1881,
			'amount' => 8500,
			'email' => 'john@example.com',
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
		$response->assertSee('with Animosity and Lethargy');
        $response->assertSee('The Mosh Pit');
        $response->assertSee('123 Example Lane');
        $response->assertSee('Laraville, ON');
        $response->assertSee('17916');
        $response->assertSee('john@example.com');

		$response->assertSee('2017-03-12');
		$response->assertSee('8:00pm');
	}

}
