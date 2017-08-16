<?php

$resource = fopen("8_input.txt", "r");

$nbStringChars = 0;
$nbEncodedChars = 0;

while (($line = fgets($resource)) !== false) {
    $line = trim($line);

    $nbStringChars += strlen($line);

    $encodedLine = '"'.addslashes($line).'"';
    
    $nbEncodedChars += strlen($encodedLine);

    // echo strlen($encodedLine)." $encodedLine ----------------- <br>";
}

$total = $nbEncodedChars - $nbStringChars;
echo "day 8.2: $total <br>"; // my result here is 1345, but I did the challenge before and my answer was 1342 ...
