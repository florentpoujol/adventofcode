<?php

declare(strict_types=1);

namespace FlorentPoujol\Adv2022\_10;

require_once 'tools.php';

$handle = fopen('10_input.txt', 'r');

startTimer();

$X = 1;
$cyclesCount = 0;

$wantedCyclesData = [
    // key = cycle, value = signal strength
    // 4 => 0,
    20 => 0,
    60 => 0,
    100 => 0,
    140 => 0,
    180 => 0,
    220 => 0,
];

while (($line = trim((string) fgets($handle))) !== '') {
    if ($line === 'noop') {
        $cyclesCount++;
        if (isset($wantedCyclesData[$cyclesCount])) {
            $wantedCyclesData[$cyclesCount] = ['x' => $X, 'strength' => $X * $cyclesCount, 'step' => 'noop'];
        }

        continue;
    }

    [$instruction, $value] = explode(' ', $line);

    if ($instruction === 'addx') {
        $cyclesCount++;
        if (isset($wantedCyclesData[$cyclesCount])) {
            $wantedCyclesData[$cyclesCount] = ['x' => $X, 'strength' => $X * $cyclesCount, 'value' => $value];
        }

        $cyclesCount++;
        if (isset($wantedCyclesData[$cyclesCount])) {
            $wantedCyclesData[$cyclesCount] = ['x' => $X, 'strength' => $X * $cyclesCount];
        }

        $X += (int) $value;
    }
}

$sum = array_sum(array_column(array_values($wantedCyclesData), 'strength'));

printDay("10.1 : $sum");

// --------------------------------------------------

rewind($handle);

startTimer();

$screen = [
    array_fill(0, 39, '.'),
    array_fill(0, 39, '.'),
    array_fill(0, 39, '.'),
    array_fill(0, 39, '.'),
    array_fill(0, 39, '.'),
    array_fill(0, 39, '.'),
];

function printScreen(): void
{
    global $screen;

    echo PHP_EOL;
    foreach ($screen as $line) {
        echo implode(' ', $line) . PHP_EOL;
    }
    echo PHP_EOL;
}

function draw(int $X, int $cycleCount): void
{
    global $screen;

    // $lineIndex = (int) (($cycleCount - 1) / 40);
    $lineIndex = match (true) {
        $cycleCount >= 201 => 5,
        $cycleCount >= 161 => 4,
        $cycleCount >= 121 => 3,
        $cycleCount >= 81 => 2,
        $cycleCount >= 41 => 1,
        default => 0,
    };

    $pixelIndex = $cycleCount < 40 ? $cycleCount : $cycleCount % 40;
    $pixelIndex--;

    $screen[$lineIndex][$pixelIndex] = $pixelIndex >= $X - 1 && $pixelIndex <= $X + 1 ? '#' : '.';
}

$X = 1;
$cyclesCount = 0;

while (($line = trim((string) fgets($handle))) !== '') {
    // noop + first cycle of addx
    $cyclesCount++;
    draw($X, $cyclesCount);

    if ($line !== 'noop') {
        [, $value] = explode(' ', $line);

        $cyclesCount++;
        draw($X, $cyclesCount);

        $X += (int) $value;
    }
}

// note that there is actually a bug, the output for the test input is missing one #
// and on the actually output, there is extra # at the end of some lines

printDay("10.2 : "); // EZFPRAKL
printScreen();
