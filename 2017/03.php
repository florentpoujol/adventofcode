<?php

$targetSquare = 289326;
// tests
//$targetSquare = 1;  // 0
//$targetSquare = 12;  // 3
//$targetSquare = 23;  // 2
//$targetSquare = 1024;  // 31

$gridWidth = (int)ceil( sqrt($targetSquare) );

$coords = ["x" => $gridWidth-1, "y" => $gridWidth-1];
$value = (int) ($gridWidth * $gridWidth); // value at the bottom right of the grid

$direction = ["x" => -1, "y" => 0];
$currentSquareWidth = $gridWidth;

/*
 36  35  34  33  32  31
 17  16  15  14  13  30
 18   5   4   3  12  29
 19   6   1   2  11  28
 20   7   8   9  10  27
 21  22  23  24  25  26
*/

while ($value >= 1) {
    if ($value === $targetSquare) { // make sure both are int !
        break;
    }

    // here we assume that top-left is 0,0
    // and the biggest number is on the bottom-right
    // we are looping in reverse from the end of the grid to number 1

    // are we at a corner ?
    if ($coords["x"] === 0 && $coords["y"] === 0) {
        // top left, needs to go right
        $direction["x"] = 1;
        $direction["y"] = 0;
    }
    elseif ($coords["x"] === 0 && $coords["y"] === $currentSquareWidth - 1) {
        // bottom left, needs to go up now
        $direction["x"] = 0;
        $direction["y"] = -1;
    }
    elseif ($coords["x"] === $currentSquareWidth - 1 && $coords["y"] === 0) {
        // top right, needs to go down
        $direction["x"] = 0;
        $direction["y"] = 1;
    }
    elseif ($coords["x"] === $currentSquareWidth - 1 && $coords["y"] === $currentSquareWidth - 2) {
        // up one square from bottom right
        // needs to go left, with new square width
        $currentSquareWidth -= 2;
        $direction["x"] = -1;
        $direction["y"] = 0;
        $coords["x"] = $currentSquareWidth;
        $coords["y"] = $currentSquareWidth - 1;
    }

    $coords["x"] += $direction["x"];
    $coords["y"] += $direction["y"];
    $value--;
}

$middle = (int)ceil($gridWidth / 2);
$centerCoords = ["x" => $middle, "y" => $middle];
if ($gridWidth % 2 === 0) {
    // grid width is even
    $centerCoords = ["x" => $middle, "y" => $middle-1];
}
if ($gridWidth === 1) {
    $centerCoords = ["x" => 0, "y" => 0];
}

$steps = abs($coords["x"] - $centerCoords["x"]) + abs($coords["y"] - $centerCoords["y"]);

echo "Day 3.1: $steps\n";


// for day 3.2, we build the grid expanding in the x and y positiv and negativ
// where 1 is at 0,0
$valuesPerCoords = [
    "0_0" => 1,
    "1_0" => 1,
];
$coords = [1, 0]; // x, y
$direction = [1, 0];

function getNeighbourValues(array $coords): array
{
    global $valuesPerCoords;
    $values = [];
    $offsets = [
        [1, 0],
        [0, 1],
        [-1, 0],
        [-1, 0],
        [0, -1],
        [0, -1],
        [1, 0],
        [1, 0],
    ];
    foreach ($offsets as $offset) {
        $coords[0] += $offset[0];
        $coords[1] += $offset[1];
        $_coords = implode("_", $coords);
        if (isset($valuesPerCoords[$_coords])) {
            $values[] = $valuesPerCoords[$_coords];
        }
    }
    return $values;
}

$sum = 0;

while ($sum < $targetSquare) {
    // time to change direction ?
    // if the next coord in the current direction has only one neighbour
    // then it is time to turn counter-clockwise
    $forwardCoords = [
        $coords[0] + $direction[0],
        $coords[1] + $direction[1],
    ];
    $neighbourValues = getNeighbourValues($forwardCoords);
    if (count($neighbourValues) === 1) {
        $y = $direction[1];
        $direction[1] = $direction[0];
        $direction[0] = 0 - $y;
    }

    $coords[0] += $direction[0];
    $coords[1] += $direction[1];
    $neighbourValues = getNeighbourValues($coords);
    $sum = array_sum($neighbourValues);
    $valuesPerCoords[ implode("_", $coords) ] = $sum;
}



echo "Day 3.2: $sum\n";