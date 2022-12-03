<?php

declare(strict_types=1);

$data = explode(PHP_EOL, file_get_contents('01_input.txt'));

$maxCalories = 0;

$caloriesForOneElf = 0;
foreach ($data as $datum) {
    if ($datum === '') {
        $maxCalories = max($maxCalories, $caloriesForOneElf);
        $caloriesForOneElf = 0;

        continue;
    }

    $caloriesForOneElf += (int) $datum;
}

echo "01.1 : max calories $maxCalories" . PHP_EOL; // 70698

// --------------------------------------------------

$caloriesPerElf = [];

$caloriesForOneElf = 0;
foreach ($data as $datum) {
    if ($datum === '') {
        $caloriesPerElf[] = $caloriesForOneElf;
        $caloriesForOneElf = 0;

        continue;
    }

    $caloriesForOneElf += (int) $datum;
}

rsort($caloriesPerElf);

$sum = array_sum(array_slice($caloriesPerElf, 0, 3));

echo "01.2 : calories for the top three elves $sum" . PHP_EOL; // 206643
