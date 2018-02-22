<?php
// http://adventofcode.com/2015/day/6

$resource = fopen("06_input.txt", "r");
$instructions = [];

while (($line = fgets($resource)) !== false) {
    $matches = [];
    preg_match("/(toggle|turn on|turn off) ([0-9,]+) through ([0-9,]+)/", $line, $matches);

    $from = explode(",", $matches[2]);
    $from[0] = (int)$from[0];
    $from[1] = (int)$from[1];
    $to = explode(",", $matches[3]);
    $to[0] = (int)$to[0];
    $to[1] = (int)$to[1];

    $instructions[] = [
        "action" => $matches[1],
        "from" => $from,
        "to" => $to,
    ];
}

$originalGrid = [];
for ($y = 0; $y < 1000; $y++) {
    $originalGrid[$y] = [];

    for ($x = 0; $x < 1000; $x++) {
        $originalGrid[$y][$x] = 0;
    }
}

// part 1

$grid = $originalGrid;
foreach ($instructions as $instr) {
    $value = $instr["action"] === "turn on" ? 1 : 0;
    $from = $instr["from"];
    $to = $instr["to"];

    for ($y = $from[1]; $y <= $to[1]; $y++) {
        for ($x = $from[0]; $x <= $to[0]; $x++) {
            if ($instr["action"] === "toggle") {
                $value = (int)!$grid[$y][$x];
            }
            $grid[$y][$x] = $value;
        }
    }
}

$litCount = 0;
for ($y = 0; $y < 1000; $y++) {
    for ($x = 0; $x < 1000; $x++) {
        if ($grid[$y][$x] === 1) {
            $litCount++;
        }
    }
}

echo "day 6.1: $litCount <br>";

// part 2

$grid = $originalGrid;
foreach ($instructions as $instr) {
    $action = $instr["action"];
    $from = $instr["from"];
    $to = $instr["to"];

    for ($y = $from[1]; $y <= $to[1]; $y++) {
        for ($x = $from[0]; $x <= $to[0]; $x++) {
            if ($action === "toggle") {
                $grid[$y][$x] += 2;
            } elseif ($action === "turn on") {
                $grid[$y][$x]++;
            } elseif ($action === "turn off" && $grid[$y][$x] > 0) {
                $grid[$y][$x]--;
            }
        }
    }
}

$brightness = 0;
for ($y = 0; $y < 1000; $y++) {
    for ($x = 0; $x < 1000; $x++) {
        $brightness += $grid[$y][$x];
    }
}

echo "day 6.2: $brightness <br>";
