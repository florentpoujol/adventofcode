<?php

declare(strict_types=1);

require_once 'tools.php';

$handle = fopen('04_input.txt', 'r');

startTimer();

$total = 0;
while (($line = trim((string) fgets($handle))) !== '') {
    [$rangeOne, $rangeTwo] = explode(',', $line, 2);

    $rangeOne = explode('-', $rangeOne, 2);
    $rangeTwo = explode('-', $rangeTwo, 2);

    if (
        ($rangeTwo[0] >= $rangeOne[0] && $rangeTwo[1] <= $rangeOne[1])
        || ($rangeOne[0] >= $rangeTwo[0] && $rangeOne[1] <= $rangeTwo[1])
    ) {
        $total++;
    }
}

printDay("04.1 : $total"); // 498

// --------------------------------------------------

startTimer();

$total = 0;
rewind($handle);
while (($line = trim((string) fgets($handle))) !== '') {
    [$rangeOne, $rangeTwo] = explode(',', $line, 2);

    $rangeOne = explode('-', $rangeOne, 2);
    $rangeTwo = explode('-', $rangeTwo, 2);

    if (
        // ....567..  5-7
        // ......789  7-9
        ($rangeTwo[0] >= $rangeOne[0] && $rangeTwo[0] <= $rangeOne[1])

        // .....67..  6-7
        // ...456...  4-6
        || ($rangeOne[0] >= $rangeTwo[0] && $rangeOne[0] <= $rangeTwo[1])
    ) {
        $total++;
    }
}

printDay("04.2 : $total"); // 859
