<?php
// http://adventofcode.com/2015/day/10

$input = "3113322113";

function process($input)
{
    $currentDigit = (int)$input[0];
    $count = 1;
    $output = [];
    $inputLength = count($input);
    for ($i = 1; $i < $inputLength; $i++) {
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
$len = count($input);
for ($i = 0; $i < $len; $i++) {
    $input[$i] = (int)$input[$i];
}

for ($i = 0; $i < 50; $i++) {
    $input = process($input);

    if ($i === 39) {
        $length = count($input);
    }
}

$length2 = count($input);

echo "day 10.1: $length  <br>";
echo "day 10.2: $length2  <br>";
