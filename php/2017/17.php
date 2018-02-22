<?php
// http://adventofcode.com/2017/day/17

$steps = 348;
//$steps = 3; // test

$buffer = [0];
$bufferLength = 1;
$id = 0;

for ($value = 1; $value <= 2017; $value++) {
    $stepsToEnd = $bufferLength - $id - 1;
    $stepsCopy = $steps;

    if ($steps > $stepsToEnd) {
        $id = 0;
        $stepsCopy = $steps - ($stepsToEnd + 1); // remaining steps, from the beginning of the buffer
        if ($stepsCopy >= $bufferLength) {
            $stepsCopy -= $bufferLength * (int)($stepsCopy / $bufferLength);
        }
    }
    $id += $stepsCopy + 1;

    array_splice($buffer, $id, 0, $value);
    $bufferLength++;
}

$value = $buffer[$id + 1];
echo "Day 17.1: $value\n";


// day 2

// on my computer, it takes a good 2 minutes to compute only 100000 elements
// so computing 50.000.000 elements would take a whole week with the same algo

$buffer = [0];
$bufferLength = 1;
$id = 0;

$zeroId = 0;
$idAfterZero = 1;
$valueAfterZero = 1;

for ($value = 1; $value <= 5E7; $value++) {
    $stepsToEnd = $bufferLength - $id - 1;
    $stepsCopy = $steps;

    if ($steps > $stepsToEnd) {
        $id = 0;
        $stepsCopy = $steps - ($stepsToEnd + 1); // remaining steps, from the beginning of the buffer
        if ($stepsCopy >= $bufferLength) {
            $stepsCopy -= $bufferLength * (int)($stepsCopy / $bufferLength);
        }
    }
    $id += $stepsCopy + 1;
    $bufferLength++;

    if ($id === $zeroId) {
        $zeroId++;
        $idAfterZero++;
    } elseif ($id === $idAfterZero) {
        $valueAfterZero = $value;
    }
}

echo "Day 17.2: $valueAfterZero\n";
