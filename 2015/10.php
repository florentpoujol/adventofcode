<?php
ini_set("memory_limit", "1000M");

$input = "3113322113";


function process($input) {
    $currentDigit = (int)$input[0];
    $count = 1;
    $output = "";
    for ($i=1; $i < count($input); $i++) { 
        if ($input[$i] === $currentDigit) {
            $count++;
        } else {
            $output[] = $count;
            // using arrays like this suppose count may not be higher than 9
            // or the total count of element will be off
            $output[] = $currentDigit;
            $currentDigit = $input[$i];
            $count = 1;
        }
    }
    $output[] = $count;
    $output[] = $currentDigit;
    // var_dump($output);
    return $output;
}

// turn input in an array if int
$input = str_split($input);
for ($i=0; $i < count($input); $i++) { 
    $input[$i] = (int)$input[$i];
}

// processing 40 loops takes 2 seconds
// processing 50 loops takes 15 seconds
for ($i=0; $i < 50; $i++) { 
    $input = process($input);

    if ($i === 39) {
        $length = count($input);
    }
}

$length2 = count($input);

echo "day 10.1: $length  <br>";
echo "day 10.2: $length2  <br>";