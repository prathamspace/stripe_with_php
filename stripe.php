<?php

require_once 'vendor/autoload.php';
require_once 'secrets.php';
// require_once 'cred.js';

$stripe = new \Stripe\StripeClient($stripeSecretKey);

function calculateOrderAmount(array $items): int
{
    $totalAmount = 0;

    foreach ($items as $item) {
        // Assuming each item has a 'price' property
        $totalAmount += $item->price;
    }

    // Convert total amount to cents (smallest currency unit)
    $totalAmountCents = round($totalAmount * 100);

    // You may apply additional logic here, such as taxes or discounts

    return $totalAmountCents;
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if JSON data is present in the request body
    $jsonStr = file_get_contents('php://input');
    if (!empty($jsonStr)) {
        // Decode the JSON data
        $jsonData = json_decode($jsonStr);

        // Check if JSON decoding was successful
        if ($jsonData === null && json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode(['error' => 'Error decoding JSON data']);
            exit;
        }

        // Ensure that $jsonData is not null before accessing its properties
        if (!isset($jsonData->items)) {
            http_response_code(400);
            echo json_encode(['error' => 'JSON data does not contain \'items\' property']);
            exit;
        }

        try {
            // Create a PaymentIntent with amount and currency
            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => calculateOrderAmount($jsonData->items),
                'currency' => 'usd',
                'payment_method_types' => ['card'],
            ]);

            $output = [
                'clientSecret' => $paymentIntent->client_secret,
            ];

            echo json_encode($output);
        } catch (Exception $e) {
            http_response_code(400); // or appropriate HTTP status code
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'No JSON data received']);
        exit;
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}
