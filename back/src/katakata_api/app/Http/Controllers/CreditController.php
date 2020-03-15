<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe;

class CreditController extends Controller
{
    public function pay(Request $request)
    {

        \Stripe\Stripe::setApiKey(config('stripe_key'));

        try {
            // Token is created using Checkout or Elements!
            // Get the payment token ID submitted by the form:
            $token = $request->input('stripeToken');
            $charge = Stripe\Charge::create([
                'amount' => $request->input('amount'),
                'currency' => 'jpy',
                'description' => 'Example charge',
                'source' => $token,
            ]);
        }catch (Stripe\Exception\CardException $e){
            // 決済に失敗したらエラーメッセージを返す
            return response()->json([
                'success' => false,
                'errors' => $e->getMessage()
            ], 422);
        }
        // Set your secret key: remember to change this to your live secret key in production
        // See your keys here: https://dashboard.stripe.com/account/apikeys
        // \Stripe\Stripe::setApiKey(config('stripe_key'));

        // try {
        //     // Token is created using Checkout or Elements!
        //     // Get the payment token ID submitted by the form:
        //     $token = $request->input('stripeToken');
        //     $charge = \Stripe\Charge::create([
        //         'amount' => $request->input('amount'),
        //         'currency' => 'jpy',
        //         'description' => 'Example charge',
        //         'source' => $token,
        //     ]);
        // }catch (\Stripe\Error\Card $e){ //元は\Stripe\Error\Card
        //     // 決済に失敗したらエラーメッセージを返す
        //     return response()->json([
        //         'success' => false,
        //         'errors' => $e->getMessage()
        //     ], 422);
        // }

        // $user_wallet = $this->exchange->cashExchange($request);

        // return response()->json([
        //     'success' => true,
        //     'data' => $user_wallet,
        //     'message' => '現金からポイントに換金が完了しました'
        // ], 200);

    }
}
