<?php

namespace App;

require __DIR__ . '/vendor/autoload.php';

try {
	$payment = new Payment('payments.csv');
	$filename = $payment->generate();
	\cli\line('Payments generated in a file saved as: ' . $filename . '%n');
} catch (\Exception $e) {
    \cli\line($e->getMessage() . '%n');
}

?>