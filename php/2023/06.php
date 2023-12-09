<?php

declare(strict_types=1);

require_once './tools.php';

$handle = fopen('input/06.txt', 'r');

$times = [];
$distances = [];
while (($line = fgets($handle)) !== false) {
    $chunks = explode(':', $line);
    $numbers = explode(' ', $chunks[1]);
    $numbers = array_map(fn (string $v) => (int) trim($v), $numbers);
    $numbers = array_values(array_filter($numbers, fn (int $v) => $v !== 0));

    if ($times === []) {
        $times = $numbers;
    } else {
        $distances = $numbers;
    }
}
// dd($times, $distances);

startTimer();
$wonRaces = [];

foreach ($times as $i => $time) {
    $targetDistance = $distances[$i];
    $wonRacesCount = 0;

    for ($speed = 1; $speed < $time; $speed++) {
        // $speed is both the time we pushed the button
        // and thus the velocity at which the boat will travel for the remaining time

        $remainingTime = $time - $speed;
        $distance = $speed * $remainingTime;

        if ($distance > $targetDistance) {
            $wonRacesCount++;
        }
    }

    $wonRaces[] = $wonRacesCount;
}

$value = 1;
foreach ($wonRaces as $wonRaceCount) {
    $value *= $wonRaceCount;
}

printDay("06.1: $value"); // 0.045 ms

// --------------------------------------------------

rewind($handle);
startTimer();

// test
// $time = 71_530;
// $targetDistance = 940_200;
// prod
$time = 55_826_490;
$targetDistance = 246_144_110_121_111;

// So of course we shouldn't here checks all possible times.
// We will check all times from the beginning until we find the first one that makes use winn,
// then we will do the same backward from the end until we find the first one that doesn't.
// That will give use the ranges of times that makes us win.

// from the start
$firstWonRace = 0;

for ($speed = 1; $speed < $time; $speed++) {
    // $speed is both the time we pushed the button
    // and thus the velocity at which the boat will travel for the remaining time

    $remainingTime = $time - $speed;
    $distance = $speed * $remainingTime;

    if ($distance > $targetDistance) {
        $firstWonRace = $speed;
        break;
    }
}

// from the end
$lastWonRace = PHP_INT_MAX;
for ($speed = $time; $speed > 0; $speed--) {
    // $speed is both the time we pushed the button
    // and thus the velocity at which the boat will travel for the remaining time

    $remainingTime = $time - $speed;
    $distance = $speed * $remainingTime;

    if ($distance > $targetDistance) {
        $lastWonRace = $speed;
        break;
    }
}


$diff = $lastWonRace - $firstWonRace + 1;
// dd($firstWonRace, $lastWonRace, $diff);

printDay("06.2: $diff"); // 1,2 s
