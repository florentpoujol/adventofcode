<?php
// http://adventofcode.com/2017/day/19

$test = "";
// $test = "_test"; // test
$resource = fopen("19_input$test.txt", "r");

$grid = [];
$maxWidth = 0;
while (($line = fgets($resource)) != false) {
    // don't trim you dummy !
    $grid[] = str_replace("\n", " ", $line);
    $maxWidth = max(strlen($line), $maxWidth);
}
$maxHeight = count($grid);

foreach ($grid as &$line) {
    $line = str_pad($line, $maxWidth, " ");
    $line = str_split($line);
}

$direction = [0, 1];
$position = [array_search("|", $grid[0]), 0];
$path = "";
$steps = 0;

while (1) {
    $steps++;
    if (!isset($grid[ $position[1] ]) || !isset($grid[ $position[1] ][ $position[0] ])) {
        var_dump($position, $direction);
        exit("out of grid position");
    }
    $char = $grid[ $position[1] ][ $position[0] ];

    if (ctype_alpha($char)) {
        $path .= $char;
    } elseif ($char === "+") {
        // change direction
        if ($direction[1] !== 0) { // currently moving vertically
            // look left and right
            if ($position[0] > 0 && $grid[ $position[1] ][ $position[0] - 1 ] !== " ") {
                $direction[0] = -1;
            } elseif ($position[0] < $maxWidth - 1 && $grid[ $position[1] ][ $position[0] + 1 ] !== " ") {
                $direction[0] = 1;
            } else {
                exit("error when looking left and right");
            }
            $direction[1] = 0;
        }
        else {
            // look up and down
            if ($position[1] > 0 && $grid[ $position[1] - 1 ][ $position[0] ] !== " ") {
                $direction[1] = -1;
            } elseif ($position[1] < $maxHeight - 1 && $grid[ $position[1] + 1 ][ $position[0] ] !== " ") {
                $direction[1] = 1;
            } else {
                exit("error when looking up and down");
            }
            $direction[0] = 0;
        }
    }

    // look ahead
    $nextChar = $grid[ $position[1] + $direction[1] ][ $position[0] + $direction[0] ];
    if ($nextChar === " ") {
        // end
        break;
    }

    $position[0] += $direction[0];
    $position[1] += $direction[1];

    if ($position[0] < 0 || $position[0] >= $maxWidth ||
        $position[1] < 0 || $position[1] >= $maxHeight) {
        var_dump($position, $direction);
        exit("position out of range");
    }
}

echo "Day 19.1: $path\n";
echo "Day 19.2: $steps\n";
