<?php

declare(strict_types=1);

require_once 'tools.php';

$handle = fopen('03_input.txt', 'r');

$priorities = 'abcdefghijklmnopqrstuvwxyz';
$priorities = ' ' . $priorities . strtoupper($priorities); // space, so that it gets the 0 index
$priorities = str_split($priorities);
$priorities = array_flip($priorities);

startTimer();

$sum = 0;
while (($line = trim((string) fgets($handle))) !== '') {
    $length = strlen($line) / 2;
    $part1 = str_split(substr($line, 0, $length));
    $part2 = str_split(substr($line, -$length));

    $common = array_values(array_unique(array_intersect($part1, $part2)));
    if (count($common) !== 1) {
        echo "ERROR" . PHP_EOL;
        dd($line, $part1, $part2, $common);
    }

    $sum += $priorities[$common[0]];
}

printDay("03.1 : $sum"); // 8109

// --------------------------------------------------
// toujours le jour 01, mais plus simple, et 2x plus rapide

startTimer();

$sum = 0;
rewind($handle);
while (($line = trim((string) fgets($handle))) !== '') {
    $length = strlen($line) / 2;
    $part1 = substr($line, 0, $length);
    $part2 = str_split(substr($line, -$length));

    foreach ($part2 as $letter) {
        if (str_contains($part1, $letter)) {
            $sum += $priorities[$letter];

            break;
        }
    }
}

printDay("03.1.2 : $sum"); // 8109

// --------------------------------------------------

startTimer();

rewind($handle);
$group = [];
$sum = 0;
while (($line = trim((string) fgets($handle))) !== '') {
    $group[] = str_split($line);

    if (count($group) === 3) {
        $common = array_values(array_unique(array_intersect(...$group)));
        if (count($common) !== 1) {
            dd($common, ...$group);
        }

        $sum += $priorities[$common[0]];

        $group = [];
    }
}

printDay("03.2 : $sum"); // 2738
