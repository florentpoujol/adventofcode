<?php
// http://adventofcode.com/2016/day/16


/*Call the data you have at this point "a".
Make a copy of "a"; call this copy "b".
Reverse the order of the characters in "b".
In "b", replace all instances of 0 with 1 and all 1s with 0.
The resulting data is "a", then a single 0, then "b".*/

function getChecksum(string $input, int $targetLength)
{
    while (strlen($input) < $targetLength) {
        $b = strrev($input);
        $b = str_replace("0", "2", $b);
        $b = str_replace("1", "0", $b);
        $b = str_replace("2", "1", $b);
        $input .= "0" . $b;
    }
    $input = substr($input, 0, $targetLength);

    // get checksum
    do {
        $checksum = "";
        for ($i = 0; $i < $targetLength; $i += 2) {
            if (isset($input[$i], $input[$i + 1])) {
                if ($input[$i] === $input[$i + 1]) {
                    $checksum .= "1";
                } else {
                    $checksum .= "0";
                }
            }
        }
        $input = $checksum;
    } while (strlen($checksum) % 2 === 0);

    return $checksum;
}

$input = "11110010111001001"; $targetLength = 272;
// $input = "10000"; $targetLength = 20; // test

$checksum = getChecksum($input, $targetLength);
echo "Day 16.1: $checksum\n";

$checksum = getChecksum($input, 35651584); // takes 30 sec to generate, no need to optimize...
echo "Day 16.2: $checksum\n";
