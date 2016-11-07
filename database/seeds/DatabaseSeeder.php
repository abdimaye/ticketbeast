<?php

use App\Concert;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

    	factory(App\Concert::class)->states('published')->create([
		    'title' => 'Example band',
		    'subtitle' => 'with fake openers',
		    'date' => \Carbon\Carbon::parse('+2 weeks'),
		    'ticket_price' => 2000,
		    'venue' => 'Example venue',
		    'venue_address' => '123 Example Lane',
		    'city' => 'Fakeville',
		    'state' => 'ON',
		    'zip' => '17916',
		    'additional_information' => 'Sample additional info.'
    	])->addTickets(10);

    }
}
