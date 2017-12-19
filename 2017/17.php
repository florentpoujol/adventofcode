<?php
// http://adventofcode.com/2017/day/17

$steps = 348;
// $steps = 3; // test

$buffer = [0];
$bufferLength = 1;
$id = 0;

for ($value = 1; $value <= 2017; $value++) {
    $stepsToEnd = $bufferLength - $id - 1;
    $_steps = $steps;

    if ($steps > $stepsToEnd) {
        $id = 0;
        $_steps = $steps - ($stepsToEnd + 1); // remaining steps, from the beginning of the buffer
        if ($_steps >= $bufferLength) {
            $_steps -= $bufferLength * (int)($_steps / $bufferLength);
        }
    }
    $id += $_steps + 1;

    array_splice($buffer, $id, 0, $value);
    $bufferLength++;
}

$value = $buffer[$id + 1];
echo "Day 17.1: $value";


// day 2

// on my computer, it takes forever to compute the buffer up to a single million elements
// I don't know to speed-up the algorithm

/*for ($value = 2018; $value <= 1E6; $value++) {
    $stepsToEnd = $bufferLength - $id - 1;
    $_steps = $steps;

    if ($steps > $stepsToEnd) {
        $id = 0;
        $_steps = $steps - ($stepsToEnd + 1); // remaining steps, from the beginning of the buffer
        if ($_steps >= $bufferLength) {
            $_steps -= $bufferLength * (int)($_steps / $bufferLength);
        }
    }
    $id += $_steps + 1;

    array_splice($buffer, $id, 0, $value);
    $bufferLength++;
}

$_id = array_search(0, $buffer);
$value = $buffer[$_id + 1];
echo "Day 17.2: $value";*/
