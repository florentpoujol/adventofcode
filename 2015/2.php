<?php

$resource = fopen("2_input.txt", "r");

$totalSurface = 0;
$ribbonLength = 0;

while (($line = fgets($resource)) !== false) {

    $lengths = explode("x", $line);
    $cb = function($v) { return (int)$v; };    
    $lengths = array_map($cb, $lengths);
    
    $surfaces = [
        $lengths[0] * $lengths[1],
        $lengths[1] * $lengths[2],
        $lengths[0] * $lengths[2]
    ];
    sort($surfaces);

    $totalSurface += $surfaces[0] * 3 + $surfaces[1] * 2 + $surfaces[2] * 2;


    sort($lengths);
    $smallestPerimeter = $lengths[0] * 2 + $lengths[1] * 2;

    $volume = $lengths[0] * $lengths[1] * $lengths[2];

    $ribbonLength += $smallestPerimeter + $volume;
}

echo "day 2.1: $totalSurface <br>";
echo "day 2.2: $ribbonLength <br>";
