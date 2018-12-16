<?php

$file = fopen(__dir__ . "/03_input.txt", "r");

$fabric = array_fill(0, 1000, 0);
for ($x = 0; $x < 1000; $x++) {
    $fabric[$x] = array_fill(0, 1000, 0);
}

// #1 @ 167,777: 23x12
$pattern = "/#([0-9]+) @ ([0-9]+),([0-9]+): ([0-9]+)x([0-9]+)/";

$coordsPerId = [];

while (($line = trim(fgets($file))) !== '') {
    $matches = [];
    preg_match($pattern, $line, $matches);
    list($_, $id, $x, $y, $w, $h) = $matches;
    $coordsPerId[$id] = [$x, $y, $w, $h];

    for ($i = $x; $i < ($x + $w); $i++) {
        for ($j = $y; $j < ($y + $h); $j++) {
            $fabric[$i][$j]++;
        }
    }
}

$overlapCellCount = 0;
foreach ($fabric as $line) {
    foreach ($line as $cell) {
        if ($cell > 1) {
            $overlapCellCount++;
        }
    }
}

echo "Day 3.1: $overlapCellCount \n";

foreach ($coordsPerId as $id => $info) {
    list($x, $y, $w, $h) = $info;

    if ($fabric[$x][$y] !== 1) {
        continue;
    }

    for ($i = $x; $i < ($x + $w); $i++) {
        for ($j = $y; $j < ($y + $h); $j++) {
            if ($fabric[$i][$j] !== 1) {
                continue(3); // go to the next  $coordsPerId
            }
        }
    }

    // if we are here, the current square doesn't overlap any other
    break;
}

echo "Day 3.2: $id \n"; // 1327 too high

