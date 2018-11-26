<?php
//php backend for stripe payments
define("STRIPE_KEY", "sk_test_fceS2G6k8wGGXSRi4JRPVJTg");

//load the autoload file
require('vendor/autoload.php');

\Stripe\Stripe::setApiKey(STRIPE_KEY);

//get post parameters
$token = $_POST['token_from_stripe'] ?: "tok_Invalid";
$amount = $_POST['amount'] ?: "00";
$description = $_POST['specialNote'] ?: "No description";

//get post parameters


try {
    $charge = \Stripe\Charge::create([
        'amount' => $amount,
        'currency' => 'usd',
        'description' => $description,
        'source' => $token,
    ]);

    //charging was successful
    $error = "false";
    $message = "Payment Successful";

    //print the data
    printData($error, $charge, $message);

} catch (\Exception $e) {

    //charging failed. catching exception
    //if the error type is a failed connection to the api. then
    //prepare a custom message
    $messageTemplate = "Could not connect to Stripe";

    if(stristr( $e->getMessage(), $messageTemplate))
    {
        //echo
        $message = "Could not connect to Stripe";
    }
    else {
        $message = $e->getMessage();
    }

    $error = $e;
    $charge = "false";

    printData($error, $charge, $message);
}

function printData($error, $charge, $message)
{
    header('Access-Control-Allow-Origin: *');
	header('Cache-Control: no-cache, must-revalidate');
	header('Content-type: application/json');

    $result = [
        "error" => $error,
        "charge" => $charge,
        "message" => $message
    ];

    echo json_encode($result);
	exit;
}



 ?>
