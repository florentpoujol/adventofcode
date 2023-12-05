<?php

declare(strict_types=1);

require_once './tools.php';

$handle = fopen('input/02.txt', 'r');

startTimer();
$sum = 0;

$target = [
    'red' => 12,
    'green' => 13,
    'blue' => 14,
];

while (($line = trim((string) fgets($handle))) !== '') {
    [$gameNumber, $sets] = explode(': ', $line, 2);
    $gameNumber = (int) str_replace('Game ', '', $gameNumber);

    $sets = explode('; ', $sets);
    foreach ($sets as $i => $set) {
        $colors = explode(', ', $set);

        foreach ($colors as $color) {
            $matches = [];
            preg_match('/^(\d+) (red|green|blue)$/', $color, $matches);
            $count = (int) $matches[1];
            if ($count > $target[$matches[2]]) {
                continue(3);
            }
        }
    }

    $sum += $gameNumber;
}

printDay("02.1: $sum"); // 0.59ms

// --------------------------------------------------

rewind($handle);
startTimer();
$sum = 0;

while (($line = trim((string) fgets($handle))) !== '') {
    [$gameNumber, $sets] = explode(': ', $line, 2);
    $gameNumber = (int) str_replace('Game ', '', $gameNumber);

    $sets = explode('; ', $sets);
    $counts = [
        'red' => 0,
        'green' => 0,
        'blue' => 0,
    ];

    foreach ($sets as $i => $set) {
        $colors = explode(', ', $set);

        foreach ($colors as $color) {
            $matches = [];
            preg_match('/^(\d+) (red|green|blue)$/', $color, $matches);
            $color = $matches[2];
            $counts[$color] = max((int) $matches[1], $counts[$color]);
        }
    }

    $power = $counts['red'] * $counts['green'] * $counts['blue'];
    // var_dump($power, $counts);
    $sum += $power;
}

printDay("02.2: $sum"); // 0.92ms
