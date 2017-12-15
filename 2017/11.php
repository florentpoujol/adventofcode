<?php
// http://adventofcode.com/2017/day/11

$instructions = explode(",", trim(file_get_contents("11_input.txt")));
// I need trim() here because PhpStorm adds a newline at the end of each files...
// and i didn't bother to dig through the settings to remove that for txt files...

// tests
// $instructions = explode(",", "ne,ne,ne"); // 3
// $instructions = explode(",", "ne,ne,sw,sw"); // 0
// $instructions = explode(",", "ne,ne,s,s"); // 2
// $instructions = explode(",", "se,sw,se,sw,sw"); // 3

$coords = [0, 0];
$maxSteps = 0;

function getSteps($coords): int
{
    $x = abs($coords[0]);
    $y = abs($coords[1]);

    $steps = $x;
    if ($x < $y) {
        $y -= $x;
        $steps += ($y / 2);
    }
    return $steps;
}

foreach ($instructions as $id => $instr) {
    switch ($instr) {
        case "n":
            $coords[1] -= 2;
            break;
        case "s":
            $coords[1] += 2;
            break;

        case "nw":
            $coords[0]--;
            $coords[1]--;
            break;
        case "ne":
            $coords[0]++;
            $coords[1]--;
            break;

        case "sw":
            $coords[0]--;
            $coords[1]++;
            break;
        case "se":
            $coords[0]++;
            $coords[1]++;
            break;
        default:
            exit("wrong instruction: $instr ($id)"); // thanks you !
            break;
    }
    $maxSteps = max($maxSteps, getSteps($coords));
}

$steps = getSteps($coords);
echo "Day 11.1: $steps\n";
echo "Day 11.2: $maxSteps\n";
