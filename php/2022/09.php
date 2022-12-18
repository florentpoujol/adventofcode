<?php

declare(strict_types=1);

namespace FlorentPoujoul\Adv2022\_09;

require_once 'tools.php';

$handle = fopen('09_input.txt', 'r');

startTimer();

$headPos = ['x' => 0, 'y' => 0];
$tailPos = ['x' => 0, 'y' => 0];

$tailVisitedPos = [
    // keys = pos "x_y", value = null;
    '0_0' => null,
];

while (($line = trim((string) fgets($handle))) !== '') {
    [$direction, $steps] = explode(' ', $line);

    for ($i = 1; $i <= $steps; $i++) {
        // move head
        if ($direction === 'U') {
            $direction = 'y';
            $negative = true;
        } elseif ($direction === 'D') {
            $direction = 'y';
            $negative = false;
        } elseif ($direction === 'L') {
            $direction = 'x';
            $negative = true;
        } elseif ($direction === 'R') {
            $direction = 'x';
            $negative = false;
        }
        assert(isset($negative));

        $previousHeadPos = $headPos;
        $headPos[$direction] += $negative ? -1 : 1;

        // check if tail needs to move
        if (
            abs($headPos['x'] - $tailPos['x']) > 1
            || abs($headPos['y'] - $tailPos['y']) > 1
        ){
            // move tail to previous head pos
            $tailPos = $previousHeadPos;
            // and register new pos
            $tailVisitedPos[$tailPos['x'] . '_' . $tailPos['y']] = null;
        }
    }
}

$visitedPositionCount = count($tailVisitedPos);
printDay("09.1 : $visitedPositionCount"); // 6023

// --------------------------------------------------

rewind($handle);

startTimer();

$knotsPos = [
    ['x' => 0, 'y' => 0], // Head

    ['x' => 0, 'y' => 0], // Tail 1
    ['x' => 0, 'y' => 0],
    ['x' => 0, 'y' => 0],
    ['x' => 0, 'y' => 0],
    ['x' => 0, 'y' => 0],
    ['x' => 0, 'y' => 0],
    ['x' => 0, 'y' => 0],
    ['x' => 0, 'y' => 0],
    ['x' => 0, 'y' => 0], // Tail 9
];

$tailVisitedPos = [
    // keys = pos "x_y", value = null;
    '0_0' => null,
];

$totalStep = 0;
while (($line = trim((string) fgets($handle))) !== '') {
    [$direction, $steps] = explode(' ', $line);

    for ($i = 1; $i <= $steps; $i++) {
        $totalStep++;
        // move head
        if ($direction === 'U') {
            $direction = 'y';
            $negative = true;
        } elseif ($direction === 'D') {
            $direction = 'y';
            $negative = false;
        } elseif ($direction === 'L') {
            $direction = 'x';
            $negative = true;
        } elseif ($direction === 'R') {
            $direction = 'x';
            $negative = false;
        }
        assert(isset($negative));

        $previousKnotPos = $knotsPos[0];
        $knotsPos[0][$direction] += $negative ? -1 : 1;

        // check if tails needs to move
        for ($tailIndex = 1; $tailIndex < 10; $tailIndex++) {
            if (
                abs($knotsPos[$tailIndex - 1]['x'] - $knotsPos[$tailIndex]['x']) > 1
                || abs($knotsPos[$tailIndex - 1]['y'] - $knotsPos[$tailIndex]['y']) > 1
            ){
                $previousKnotPosTemp = $knotsPos[$tailIndex];
                // move tail to previous head pos
                $knotsPos[$tailIndex] = $previousKnotPos;
                $previousKnotPos = $previousKnotPosTemp;

                if ($tailIndex === 9) {
                    // and register new pos
                    $tailVisitedPos[$knotsPos[$tailIndex]['x'] . '_' . $knotsPos[$tailIndex]['y']] = null;
                }
            } else {
                break;
            }
        }

        if ($totalStep === 6) {
            dd($knotsPos);
        }
    }
}

// note : doesn't work, because when 2 knots are separated, the next one MUST move diagonally, this is an actual rule

$visitedPositionCount = count($tailVisitedPos);
printDay("09.2 : $visitedPositionCount"); // 6023
