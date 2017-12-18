<?php
// http://adventofcode.com/2017/day/13

$resource = fopen("13_input.txt", "r");
$rangeByDepth = [];

while (($line = fgets($resource)) !== false) {
    $parts = explode(": ", $line);
    $rangeByDepth[(int)$parts[0]] = (int)$parts[1];
}

// $rangeByDepth = [0 => 3, 1 => 2, 4 => 4, 6 => 4]; // test

// give the scanner position in its range BEFORE it has moved at that time
// position is >= 0   0 is the top of the range
// $time is >= 0
function getScannerPositionBeforeMove(int $time, int $range): int
{
    $t = ($range * 2 - 2); // time it take to move back and forth once along the range
    if ($time > $t) {
        $count = (int)($time / $t);
        $time -= $count * $t; // doing this speeds the function up like a billion-fold
    }

    $position = 0; // top of the range
    $direction = 1;
    $lastRangeId = $range - 1;
    for ($i = 1; $i <= $time; $i++) {
        $position += $direction;

        if ($position === 0 || $position === $lastRangeId) {
            $direction = 0 - $direction;
        }
    }
    return $position;
}

// some unit tests
if (getScannerPositionBeforeMove(0, 4) !== 0) {
    exit ("scanner test 1");
}
if (getScannerPositionBeforeMove(1, 4) !== 1) {
    exit ("scanner test 2");
}
if (getScannerPositionBeforeMove(4, 4) !== 2) {
    exit ("scanner test 3");
}
if (getScannerPositionBeforeMove(6, 4) !== 0) {
    exit ("scanner test 4");
}
if (getScannerPositionBeforeMove(7, 4) !== 1) {
    exit ("scanner test 5");
}
if (getScannerPositionBeforeMove(11, 4) !== 1) {
    exit ("scanner test 6");
}


$currentDepth = -1;
$severity = 0;

for ($depth = 0; $depth < 100; $depth++) {
    $scannerPos = -1;
    $range = $rangeByDepth[$depth] ?? null;
    if ($range !== null) {
        $scannerPos = getScannerPositionBeforeMove($depth, $range);

        if ($scannerPos === 0) {
            // he scanner at this depth is at the top of its range, thus we get caught
            $severity += $depth * $range;
        }
    }

    $currentDepth = $depth;
}

echo "Day 13.1: $severity\n";

// If there is a scanner at the top of the layer as your packet enters it, you are caught.
// If a scanner moves into the top of its layer while you are there,       you are not caught

$maxDepth = 0;
for ($delay = 0; $delay < 9999999; $delay++) {
    $packetDepth = -1;
    $time = $delay - 1;

    while (1) {
        $packetDepth++;
        $time++;

        $range = $rangeByDepth[$packetDepth] ?? null;
        if ($range !== null) {
            $scannerPos = getScannerPositionBeforeMove($time, $range);

            if ($scannerPos === 0) {
                // the scanner at this depth is at the top of its range, thus we get caught
                $maxDepth = max($maxDepth, $packetDepth);
                break; // break the while,
            }
        }

        if ($packetDepth >= 100) {
            break(2);
        }
    }
}

echo "Day 13.2: $delay | $maxDepth\n"; // 3823370
