<?php

namespace App;

class RandomOrderConfirmationNumberGenerator implements OrderConfirmationNumberGenerator
{
	public function generate($length = 24)
	{
		$pool = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

		return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
	}
}