<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{

	protected $guarded = [];
	
    public function scopeAvailable($query)
    {
    	$query->whereNull('order_id');
    }
}
