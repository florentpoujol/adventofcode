<?php
// http://adventofcode.com/2015/day/3

$input = str_split(file_get_contents("03_input.txt", "r"));

$coords = [0, 0];
$visitedLocations = ["0_0"];
$houses = 1;

foreach ($input as $i => $direction) {
    switch ($direction) {
        case '^':
            $coords[1]++;
            break;
        case 'v':
            $coords[1]--;
            break;
        case '>':
            $coords[0]++;
            break;
        case '<':
            $coords[0]--;
            break;
    }

    $location = $coords[0] . "_" . $coords[1];

    if (! in_array($location, $visitedLocations)) {
        $houses++;
        $visitedLocations[] = $location;
    }
}

echo "day 3.1: $houses <br>";

// part 2

$santaCoords = [0, 0];
$roboCoords = [0, 0];
$visitedLocations = ["0_0"];
$visitedHouses = 1;
$robosTurn = true;

foreach ($input as $i => $direction) {
    $robosTurn = ! $robosTurn;
    if ($robosTurn) {
        $coords = &$roboCoords;
    } else {
        $coords = &$santaCoords;
    }

    switch ($direction) {
        case '^':
            $coords[1]++;
            break;
        case 'v':
            $coords[1]--;
            break;
        case '>':
            $coords[0]++;
            break;
        case '<':
            $coords[0]--;
            break;
    }

    $location = $coords[0] . "_" . $coords[1];

    if (! in_array($location, $visitedLocations)) {
        $visitedHouses++;
        $visitedLocations[] = $location;
    }
}

echo "day 3.2: $visitedHouses <br>";
