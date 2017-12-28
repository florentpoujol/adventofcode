<?php
// http://adventofcode.com/2015/day/20
$targetPresentCount = 36000000;
$targetHouseId = -1;

$elfCount = 999999; // to be increased if the target present count is not reached

$presentCountPerHouseNb = array_fill(1, $elfCount, 0);
$elfId = 1;

for ($elfId = 1; $elfId <= $elfCount; $elfId++) {
    for ($houseId = $elfId; $houseId <= $elfCount; $houseId += $elfId) {
        $presentCountPerHouseNb[$houseId] += $elfId * 10;

        if ($presentCountPerHouseNb[$houseId] >= $targetPresentCount) {
            $targetHouseId = $houseId;
            break 2;
        }
    }
}

echo "Day 20.1: $targetHouseId (elfId: $elfId)\n";

// part 2
$targetHouseId = -1;

$elfCount = 999999; // to be increased if the target present count is not reached

$presentCountPerHouseNb = array_fill(1, $elfCount, 0);
$elfId = 1;

for ($elfId = 1; $elfId <= $elfCount; $elfId++) {
    $houseCount = 50;
    for ($houseId = $elfId; $houseId <= $elfCount && $houseCount-- > 0; $houseId += $elfId) {
        $presentCountPerHouseNb[$houseId] += $elfId * 11;

        if ($presentCountPerHouseNb[$houseId] >= $targetPresentCount) {
            $targetHouseId = $houseId;
            break 2;
        }
    }
}

echo "Day 20.2: $targetHouseId (elfId: $elfId)\n";
