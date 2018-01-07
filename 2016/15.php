<?php
// http://adventofcode.com/2016/day/15

// if holes must be in position 0 when the ball is at their level
// we want the first hole at position -1 when the ball starts
// the second hole must be at -2, etc...


$input = [
    // targetPos is the position the disc must be at when the ball starts to drop
    // count - arrayId - 1
    ["count" => 17, "start" => 15, "targetPos" => 16],
    ["count" => 3, "start" => 2, "targetPos" => 1],
    ["count" => 19, "start" => 4, "targetPos" => 16],
    ["count" => 13, "start" => 2, "targetPos" => 9],
    ["count" => 7, "start" => 2, "targetPos" => 2],
    ["count" => 5, "start" => 0, "targetPos" => 4],
];

$test = false;
// $test = true;
if ($test) {
    $input = [
        ["count" => 5, "start" => 4, "targetPos" => 4],
        ["count" => 2, "start" => 1, "targetPos" => 0],
    ];
}

function getDiscPosition(int $discId, int $time): int
{
    global $input;
    $infos = $input[$discId];
    return ($infos["start"] + $time) % $infos["count"];
}

// get the next time after or at the provided time that the given disc will be at its target position
function getNextTime(int $discId, int $time): int
{
    global $input;
    $infos = $input[$discId];

    $currentPosition = getDiscPosition($discId, $time);
    $diff = $infos["targetPos"] - $currentPosition;
    if ($diff >= 0) {
        return $time + $diff;
    }

    return $time + $infos["count"] + $diff;
}

// test getDiscoPosition() and getNextTime()
/*for ($i = 0; $i < 20; $i++) {
    echo str_pad($i, 2) . " ";
}
echo "\n";
$discId = 3;
for ($i = 0; $i < 20; $i++) {
    echo str_pad(getDiscPosition($discId, $i), 2) . " ";
}
echo "\n";
for ($i = 0; $i < 20; $i++) {
    echo str_pad(getNextTime($discId, $i), 2) . " ";
}
echo "\n";*/


$earliestTime = -1;
$maxTime = 999999;
for ($time = getNextTime(0, 1); $time < $maxTime; $time += $input[0]["count"]) {
    if (
        getDiscPosition(1, $time) === $input[1]["targetPos"] &&
        getDiscPosition(2, $time) === $input[2]["targetPos"] &&
        getDiscPosition(3, $time) === $input[3]["targetPos"] &&
        getDiscPosition(4, $time) === $input[4]["targetPos"] &&
        getDiscPosition(5, $time) === $input[5]["targetPos"]
    ) {
        $earliestTime = $time;
        break;
    }
}

echo "Day 15.1: $earliestTime\n";

// part 2, just one more loop

$input[] = ["count" => 11, "start" => 0, "targetPos" => 4];

$earliestTime = -1;
$maxTime = 9999999;
for ($time = getNextTime(0, 1); $time < $maxTime; $time += $input[0]["count"]) {

    if (
        getDiscPosition(1, $time) === $input[1]["targetPos"] &&
        getDiscPosition(2, $time) === $input[2]["targetPos"] &&
        getDiscPosition(3, $time) === $input[3]["targetPos"] &&
        getDiscPosition(4, $time) === $input[4]["targetPos"] &&
        getDiscPosition(5, $time) === $input[5]["targetPos"] &&
        getDiscPosition(6, $time) === $input[6]["targetPos"]
    ) {
        $earliestTime = $time;
        break;
    }
}

echo "Day 15.2: $earliestTime\n";
