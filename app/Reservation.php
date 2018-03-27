<?php

namespace App;

class Reservation
{
	private $tickets;

	public function __construct($tickets)
	{
	    $this->tickets = $tickets;
	}
	
	public function totalCost($value='')
	{
		return $this->tickets->sum('price');
	}

}