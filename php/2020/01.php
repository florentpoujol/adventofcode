<?php

$originalLines = file('01_input.txt'); // read all file into an array
$answer = 0;

foreach ($originalLines as $i => $line) {
    $currentNumber = (int)trim($line);

    $numbersToCompareWith = $originalLines; // copy
    array_splice($numbersToCompareWith, $i, 1); // remove current number

    foreach ($numbersToCompareWith as $number) {
        $number = (int)$number;
        // echo $currentNumber . ' ' . $number . PHP_EOL;

        if ($currentNumber + $number === 2020) {
            $answer = $currentNumber * $number;

            break(2);
        }
    }
}

echo "Day 1.1: $answer \n";

$answer = 0;

foreach ($originalLines as $i => $line1) {
    $line1 = (int)trim($line1);

    foreach ($originalLines as $j => $line2) {
        if ($j === $i) {
            continue;
        }

        $line2 = (int)trim($line2);
        if ($line1 + $line2 >= 2020) {
            continue; // takes a bazillion times more without this
        }

        foreach ($originalLines as $k => $line3) {
            if ($k === $j) {
                continue;
            }

            $line3 = (int)trim($line3);

            if ($line1 + $line2 + $line3 === 2020) {
                $answer = $line1 * $line2 * $line3;

                break(3);
            }
        }
    }
}

echo "Day 1.2: $answer \n";
