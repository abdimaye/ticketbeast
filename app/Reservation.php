<?php

namespace App;

use App\Order;

class Reservation
{
	private $tickets;
	private $email;

	public function __construct($tickets, $email)
	{
	    $this->tickets = $tickets;
	    $this->email = $email;
	}

	public function totalCost($value='')
	{
		return $this->tickets->sum('price');
	}

	public function complete()
	{
		return $order = Order::forTickets($this->tickets(), $this->email(), $this->totalCost());
	}

	public function cancel()
	{
		foreach ($this->tickets as $ticket) {
			$ticket->release();
		}
	}

	public function tickets()
	{
		return $this->tickets;
	}

	public function email()
	{
		return $this->email;
	}

}