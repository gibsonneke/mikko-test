<?php

namespace App;

require __DIR__ . '/vendor/autoload.php';

$payment = new Payment();
$filename = $payment->generate();
\cli\line('File saved as: ' . $filename . '%n');

?>