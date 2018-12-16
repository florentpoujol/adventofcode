<?php

$resource = fopen("01.1_input.txt", "r");

$frequency  = 0;
$i = 0;
$changes = [];

while (($line = trim(fgets($resource))) !== '') {
    $frequency += (int)$line;
    $changes[] = (int)$line;
    $i++;
}

echo "Day 1.1: $frequency $i\n";

$frequency = 0;
$allUniqueFrequencies = [];
$i = 0;
$j = 0;

do {
    foreach ($changes as $change) {
        $frequency += $change;

        if (in_array($frequency, $allUniqueFrequencies)) {
            break(2);
        }
        $allUniqueFrequencies[] = $frequency;
        $i++;
    }

    $j++;
} while(1);

echo "Day 1.2: $frequency $j $i\n";
