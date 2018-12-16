<?php

$resource = fopen(__dir__ . "/02_input.txt", "r");

$twoLettersCount = 0;
$threeLettersCount = 0;

/** @var string[] */
$boxIds = [];

while (($line = trim(fgets($resource))) !== '') {
    $countPerLetters = [];

    $lineLength = strlen($line);
    for ($i = 0; $i < $lineLength; $i++) {
        $letter = $line[$i];
        if (!isset($countPerLetters[$letter])) {
            $countPerLetters[$letter] = 0;
        }

        $countPerLetters[$letter]++;
    }

    $twoSameLettersFound = false;
    $threeSameLettersFound = false;
    foreach ($countPerLetters as $letter => $count) {
        if ($twoSameLettersFound && $threeSameLettersFound) {
            continue(2);
        }

        if ($count === 2 || $count === 3) {
            $boxIds[] = $line;
        }

        if ($count === 2 && $twoSameLettersFound === false) {
            $twoLettersCount++;
            $twoSameLettersFound = true;
        } elseif ($count === 3 && $threeSameLettersFound === false) {
            $threeLettersCount++;
            $threeSameLettersFound = true;
        }
    }
}

$checksum = $twoLettersCount * $threeLettersCount;

echo "Day 2.1: $checksum = $twoLettersCount * $threeLettersCount \n";

$boxIds = array_unique($boxIds);
$diffs = [];

foreach ($boxIds as $box) {
    foreach ($boxIds as $box2) {
        if ($box === $box2) {
            continue;
        }

        $diff = array_diff_assoc(str_split($box), str_split($box2));
        if (count($diff) === 1) {
            //$diffs[] = "$box $box2 " . implode('', $diff);
            break(2);
        }
    }
}

$commonLetters = implode('', array_intersect_assoc(str_split($box), str_split($box2)));

echo "Day 2.2: $commonLetters \n";
