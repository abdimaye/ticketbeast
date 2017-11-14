<?php

namespace App\Http\Controllers;

use App\Concert;
use Illuminate\Http\Request;

class ConcertsController extends Controller
{
    public function show($id)
    {
    	// findOfFail will return a 404 if model is not found
    	$concert = Concert::whereNotNull('published_at')->findOrFail($id);

    	return view('concerts.show', ['concert' => $concert]);
    }
}
