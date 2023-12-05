<?php

declare(strict_types=1);

require_once './tools.php';

$handle = fopen('input/03.txt', 'r');

startTimer();
$sum = 0;

$map = [];
while (($line = trim((string) fgets($handle))) !== '') {
    $map[] = str_split($line);
}

// parse the map to do two things :
// - register every possible numbers and the coordinates of their digits
// - register the position of every symbols, and build a list of the coordinates where we could find a number that counts
//
// So that we will be able later to go through all number and the coordinates to check if they count

$currentNumber = '';
/** @var array<string> $currentNumberCoordinates */
$currentNumberCoordinates = [];

$coordinatesPerNumbers = [
    // keys are numbers, as string
    // values are array<array<string>>, since the keys may appear multiple times in the input
];

$valuablesCoordinatesWithNumbers = [
    // keys are the coordinates as string in format x_y. Ie: 1_2.
    // values are the number that we find at this coordinate (for debug)
];

foreach ($map as $y => $chars) {
    foreach ($chars as $x => $char) {
        if (is_numeric($char)) {
            $currentNumber .= $char;
            $currentNumberCoordinates[] = "{$x}_$y";

            continue;
        }

        if ($char !== '.') {
            // this is a symbol
            // build list of all its surrounding coordinates where we find a number
            for ($i = $x - 1; $i <= $x + 1 ; $i++) {
                for ($j = $y - 1; $j <= $y + 1 ; $j++) {
                    if ($i === $x && $j === $y) {
                        continue;
                    }

                    $stringCoord = "{$i}_$j";
                    // var_dump("$y $x $j $i " . $map[$j][$i]);
                    if (! isset($valuablesCoordinatesWithNumbers[$stringCoord]) && is_numeric($map[$j][$i])) {
                        $valuablesCoordinatesWithNumbers[$stringCoord] = $map[$j][$i];
                        // var_dump($stringCoord, $x, $y, $char);
                    }
                }
            }
        }

        if ($currentNumber !== '') {
            $coordinatesPerNumbers[$currentNumber] ??= [];
            $coordinatesPerNumbers[$currentNumber][] = $currentNumberCoordinates;

            $currentNumber = '';
            $currentNumberCoordinates = [];
        }
    }
}

// now loop on the saved numbers and the coordinates of their digits
// if we found only one that match the saved interesting coordinates, then consider this number
foreach ($coordinatesPerNumbers as $number => $coordSets) {
    foreach ($coordSets as $coordSet) {
        foreach ($coordSet as $stringCoord) {
            if (isset($valuablesCoordinatesWithNumbers[$stringCoord])) {
                $sum += $number;
                continue(2);
            }
        }
    }
}

// var_dump($map, $coordinatesPerNumbers);

printDay("03.1: $sum"); // 7.1 ms

// --------------------------------------------------

rewind($handle);
startTimer();
$sum = 0;

// TODO

printDay("03.2: $sum");
