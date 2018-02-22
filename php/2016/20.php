<?php
// https://adventofcode.com/2016/day/20

$lines = explode("\n", file_get_contents("20_input.txt"));

$blockedRanges = [];
foreach ($lines as $line) {
    $boundaries = explode("-", $line);
    $min = (int)$boundaries[0];
    if (isset($blockedRanges[$min])) {
        var_dump($min);
    }
    $blockedRanges[$min] = (int)$boundaries[1];
}
ksort($blockedRanges);
// var_dump($blockedRanges);

$allowedRanges = [];
$maxBlockedValue = 0;

foreach ($blockedRanges as $min => $max) {
    if ($min - 1 > $maxBlockedValue) {
        $allowedRanges[] = [$maxBlockedValue + 1, $min - 1];
    }
    if ($max > $maxBlockedValue) {
        $maxBlockedValue = $max;
    }
}

$minValue = $allowedRanges[0][0]; // 5.741.645

echo "Day 20.1: $minValue\n";

// part 2

$allowedIPs = 0;

foreach ($allowedRanges as $bounds) {
    $min = $bounds[0];
    $max = $bounds[1];
    $allowedIPs += $max - $min + 1;
}

echo "Day 20.2: $allowedIPs\n";
