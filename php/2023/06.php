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

$sum = 1;
foreach ($wonRaces as $wonRaceCount) {
    $sum *= $wonRaceCount;
}

printDay("06.1: $sum"); //

// --------------------------------------------------

rewind($handle);
startTimer();



printDay("06.1: $sum");
