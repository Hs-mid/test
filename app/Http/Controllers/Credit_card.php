<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal;

class Credit_card extends Controller
{
    public function create(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $provider = \PayPal::setProvider();
        $provider->setApiCredentials(config('paypal'));
        $token = $provider->getAccessToken();
        $provider->setAccessToken($token);

        $price = $data['value'];
        $description = 'test';

        $order = $provider->createOrder([
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => $price,
                    ],
                    'description' => $description,
                ],
            ],
        ]);

        return response()->json($order);
    }

    public function capture(Request $request)
    {
      
        $data = json_decode($request->getContent(), true);
        $orderID = $data['orderID'];
        $provider = \PayPal::setProvider();
        $provider->setApiCredentials(config('paypal'));
        $token = $provider->getAccessToken();
        
        $provider->setAccessToken($token);
        $result = $provider->capturePaymentOrder($orderID);

        // save data
        if ($result['status'] === 'COMPLETED') {

            return response()->json(['status' => 'success', 'message' => 'Payment captured successfully']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Payment capture failed']);
        }
    }
}
