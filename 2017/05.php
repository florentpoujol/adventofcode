<?php
$resource = fopen("05_input.txt", "r");

$instructions = [];
while (($line = fgets($resource)) !== false) {
    $line = trim($line);
    if ($line === "") {
        break;
    }
    $instructions[] = (int)$line;
}
$baseInstructions = $instructions; // used for part 2


$instrId = 0;
$lastInstrId = count($instructions) - 1;
$steps = 0;
while ($instrId >= 0 && $instrId <= $lastInstrId) {
    $offset = $instructions[$instrId];
    $instructions[$instrId]++;
    $instrId += $offset;
    $steps++;
}

echo "Day 5.1: $steps \n";

// part 2

$instructions = $baseInstructions;
$instrId = 0;
$lastInstrId = count($instructions) - 1;
$steps = 0;
while ($instrId >= 0 && $instrId <= $lastInstrId) {
    $offset = $instructions[$instrId];
    if ($offset >= 3) {
        $instructions[$instrId]--;
    } else {
        $instructions[$instrId]++;
    }
    $instrId += $offset;
    $steps++;
}

echo "Day 5.2: $steps \n";