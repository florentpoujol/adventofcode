<?php

$input = 289326;
// tests
// $input = 1;  // 0
// $input = 12;  // 3
// $input = 23;  // 2
// $input = 1024;  // 31


$width = ceil( sqrt($input) );

$coords = ["x" => $width, "y" => $width];
$value = $width * $width;
$direction = ["x" => -1, "y" => 0];
$currentSquareWidth = $wdith;

while ($value >= 1) {
    if ($value === $input) {
        break;
    }

    
    // are we at a edge ?
    if ($coords["x"] === 0 || $coords["x"] === $currentSquareWidth || $coords["y"] === 0 || $coords["y"] === $currentSquareWidth) {


        
    }


    $coords["x"] += $direction["x"];
    $coords["y"] += $direction["y"];
}


$centerCoord = ceil($width / 2);

$steps = abs($coords["x"] - $centerCoord) + abs($coords["y"] - $centerCoord);

echo "Day 3.1: $steps\n";
