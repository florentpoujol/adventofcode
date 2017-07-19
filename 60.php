<?php

$res = fopen("60_input.txt", "r");
// for each columns, holds an array "freq by letter"
$frequenciesByColumn = [];
$size = 0;

while (($line = fgets($res)) !== false) {
    $line = trim($line);

    if ($size === 0) {
        $size = strlen($line);
    }

    $letters = str_split($line);

    for ($col=0; $col < $size; $col++) {
        
        if (! array_key_exists($col, $frequenciesByColumn)) {
            $frequenciesByColumn[$col] = [];
        }

        $letter = $letters[$col];
        $freqByLetter =& $frequenciesByColumn[$col];

        if (! array_key_exists($letter, $freqByLetter)) {
            $freqByLetter[$letter] = 1;
        } else {
            $freqByLetter[$letter]++;
        }
    }
}

unset($freqByLetter);
// note: using the same name $freqByLetter in the loop below would cause the last 
// array in $frequenciesByColumn to be replaced by the previous-to-last array
// because I create the var by reference in the previous loop.
//
// What happens is that $freqByLetter still point to the same value as the last key in $frequenciesByColumn
// In the loop below, assignment to the different values of $frequenciesByColumn also reassign the last element of that array
// so when it is time to loop one last time, the last value = the previous-to-last value
//
// solutions:
// - use a different var name in the loop below
// - in the previous loop, assing by value (copy) then set the new $freqByLetter back to $frequenciesByColumn[$col] after
// - unset $freqByLetter after the previous loop

$msg = "";
$msg2 = "";

foreach ($frequenciesByColumn as $freqByLetter) {

    $maxCount = 0;
    $mostCommonLetter = "-";

    $minCount = 99;
    $leastCommonLetter = "-";

    array_filter(
        $freqByLetter, 
        function($count, $letter) {
            global $maxCount, $mostCommonLetter, $minCount, $leastCommonLetter;
            
            if ($count > $maxCount) {
                $maxCount = $count;
                $mostCommonLetter = $letter;
            }

            if ($count < $minCount) {
                $minCount = $count;
                $leastCommonLetter = $letter;
            }
        }, 
        ARRAY_FILTER_USE_BOTH
    );

    $msg .= $mostCommonLetter;
    $msg2 .= $leastCommonLetter;
}

echo "day 6.1: $msg<br>";
echo "day 6.2: $msg2<br>";