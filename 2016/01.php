<?php
// http://adventofcode.com/2016/day/1

$instructions = ["L2", "L5", "L5", "R5", "L2", "L4", "R1", "R1", "L4", "R2", "R1", "L1", "L4", "R1", "L4", "L4", "R5", "R3", "R1", "L1", "R1", "L5", "L1", "R5", "L4", "R2", "L5", "L3", "L3", "R3", "L3", "R4", "R4", "L2", "L5", "R1", "R2", "L2", "L1", "R3", "R4", "L193", "R3", "L5", "R45", "L1", "R4", "R79", "L5", "L5", "R5", "R1", "L4", "R3", "R3", "L4", "R185", "L5", "L3", "L1", "R5", "L2", "R1", "R3", "R2", "L3", "L4", "L2", "R2", "L3", "L2", "L2", "L3", "L5", "R3", "R4", "L5", "R1", "R2", "L2", "R4", "R3", "L4", "L3", "L1", "R3", "R2", "R1", "R1", "L3", "R4", "L5", "R2", "R1", "R3", "L3", "L2", "L2", "R2", "R1", "R2", "R3", "L3", "L3", "R4", "L4", "R4", "R4", "R4", "L3", "L1", "L2", "R5", "R2", "R2", "R2", "L4", "L3", "L4", "R4", "L5", "L4", "R2", "L4", "L4", "R4", "R1", "R5", "L2", "L4", "L5", "L3", "L2", "L4", "L4", "R3", "L3", "L4", "R1", "L2", "R3", "L2", "R1", "R2", "R5", "L4", "L2", "L1", "L3", "R2", "R3", "L2", "L1", "L5", "L2", "L1", "R4"];
// tests
// $instructions = ["R2", "L3"];
// $instructions = ["R2", "R2", "R2"];
// $instructions = ["R5", "L5", "R5", "R3"];
// $instructions = ["R8", "R4", "R4", "R8"];

$direction = "north";
$coords = [0, 0];

$part2Coords = null;
$visitedLocations = [];

$L = [
    "north" => "west",
    "west" => "south",
    "south" => "east",
    "east" => "north",
];
$R = [
    "north" => "east",
    "west" => "north",
    "south" => "west",
    "east" => "south",
];


foreach ($instructions as $instr) {
    $lastCoords = $coords;
    $nextCoords = $coords; // coords 1 steps after the last coords in the new direction

    $direction = ${$instr[0]}[$direction];
    $count = (int)substr($instr, 1);
    switch ($direction) {
        case "north":
            $coords[1] -= $count;
            $nextCoords[1]--;
            break;
        case "east":
            $coords[0] += $count;
            $nextCoords[0]++;
            break;
        case "south":
            $coords[1] += $count;
            $nextCoords[1]++;
            break;
        case "west":
            $coords[0] -= $count;
            $nextCoords[0]--;
            break;
        default:
            exit("wrong direction: $direction");
    }

    if ($part2Coords === null) {
        if ($lastCoords[0] !== $coords[0]) {
            $increment = $coords[0] >= $lastCoords[0] ? 1 : -1;
            for ($i = $lastCoords[0]; $i !== $coords[0]; $i += $increment) {
                $location = $i . "_" . $lastCoords[1];

                if (in_array($location, $visitedLocations)) {
                    $part2Coords = [$i, $lastCoords[1]];
                    break;
                }
                $visitedLocations[] = $location;
            }
        } else {
            $increment = $coords[1] >= $lastCoords[1] ? 1 : -1;
            for ($i = $lastCoords[1]; $i !== $coords[1]; $i += $increment) {
                $location = $lastCoords[0] . "_" . $i;

                if (in_array($location, $visitedLocations)) {
                    $part2Coords = [$lastCoords[0], $i];
                    break;
                }
                $visitedLocations[] = $location;
            }
        }
    }
}

// var_dump($coords);
$distance = abs($coords[0]) + abs($coords[1]);
echo "Day 01.1: $distance\n";

// var_dump($visitedLocations);
// var_dump($part2Coords);
$distance = abs($part2Coords[0]) + abs($part2Coords[1]);
echo "Day 01.2 $distance\n";
