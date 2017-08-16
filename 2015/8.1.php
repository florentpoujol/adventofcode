<?php

$resource = fopen("8_input.txt", "r");

$nbStringChars = 0;
$nbMemoryChars = 0;

while (($line = fgets($resource)) !== false) {
    $line = trim($line);

    $nbStringChars += strlen($line);

    $line = trim($line, '"'); // if the end of the string is \"", only the \ will remains, but it will be counted as one char, which is want is wanted
    $nbChars = strlen($line);

    // detect escape sequences and reduce the nbChars accordingly

    $matches = [];
    preg_match_all("/\\\\\\\\/", $line, $matches); // look for \\
    // var_dump($matches);
    $nbChars -= count($matches[0]);
    
    $matches = [];
    preg_match_all('/\\\\"/', $line, $matches);
    // var_dump($matches);
    $nbChars -= count($matches[0]);


    $matches = [];
    preg_match_all("/\\\\x[0-9a-f]{2}/", $line, $matches);   
    // var_dump($matches);
    $nbChars -= count($matches[0]) * 3;

    // echo "$nbChars $line --------------------------------------<br>";
    $nbMemoryChars += $nbChars;
}

$total = $nbStringChars - $nbMemoryChars;
echo "day 8.1: $total <br>"; // my result here is 1345, but I did the challenge before and my answer was 1342 ...
