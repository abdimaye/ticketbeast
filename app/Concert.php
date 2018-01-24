<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\NotEnoughTicketException;

class Concert extends Model
{
    protected $guarded = [];

    protected $dates = ['date'];

    public function scopePublished($query)		
    {
    	return $query->whereNotNull('published_at');
    }

    public function getFormattedDateAttribute()
    {
    	return $this->date->format('F j, Y');
    }

    public function getFormattedStartTimeAttribute()
    {
    	return $this->date->format('g:ia');
    }

    public function getTicketPriceInDollarsAttribute()
    {
    	return number_format($this->ticket_price / 100, 2);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function orderTickets($email, $ticketQuantity)
    {
        $tickets = $this->tickets()->available()->take($ticketQuantity)->get();

        // Are enough tickets available
        if ($tickets->count() < $ticketQuantity) {
            throw new NotEnoughTicketException;
            
        }

        // Create the order
        $order = $this->orders()->create(['email' => $email]);

        foreach($tickets as $ticket) {
            $order->tickets()->save($ticket);
        }

        return $order;
    }

    public function addTickets($quantity)
    {
        foreach(range(1, $quantity) as $i) {
            $this->tickets()->create([]);
        }
    }

    public function ticketsRemaining()
    {
        return $this->tickets()->available()->count();
    }
}
