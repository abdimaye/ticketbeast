<?php

use App\Billing\StripePaymentGateway;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StripePaymentGatewayTest extends TestCase
{
    /** @test */
    function charges_with_a_valid_payment_token_are_successful()
    {
        // Create a new StripPaymentGateway
    	$paymentGateway = new StripePaymentGateway(config('services.stripe.secret'));

    	$token = \Stripe\Token::create([
            "card" => [
                "number" => "4242424242424242",
                "exp_month" => 1,
                "exp_year" => date('Y') + 1,
                "cvc" => "123"
            ]
        ], ['api_key' => config('services.stripe.secret')])->id;

        // Create a new charge for some acmount using a valid token
        $paymentGateway->charge(2500, $token);

        // Verify that the charge was completed
        $lastCharge = \Stripe\Charge::all(
        	['limit' => 1],
        	['api_key' => config('services.stripe.secret')]
        )['data'][0];

        $this->assertEquals(2500, $lastCharge->amount);
    }
}