<?php
// http://adventofcode.com/2015/day/17

$containers = [150, 43, 3, 4, 10, 21, 44, 4, 6, 47, 41, 34, 17, 17, 44, 36, 31, 46, 9, 27, 38];
// $containers = [25, 20, 15, 10, 5, 5]; // test

$targetCapacity = array_shift($containers); // 150, or 25 for test input
rsort($containers);

$minCombinationSize = 999;
$combinations = [];

function run(int $currentCapacity, array $combination, array $remainingContainers)
{
    if (empty($remainingContainers)) {
        return;
    }

    global $targetCapacity, $combinations, $minCombinationSize;

    while (!empty($remainingContainers)) {
        $_combination = $combination;

        $container = array_shift($remainingContainers);
        $newCapacity = $currentCapacity + $container;
        $_combination[] = $container;

        if ($newCapacity < $targetCapacity) {
            run($newCapacity, $_combination, $remainingContainers);
        }
        elseif ($newCapacity === $targetCapacity) {
            $combinations[] = $_combination;
            $minCombinationSize = min($minCombinationSize, count($_combination));
        }
    }
}

run(0, [], $containers);

// var_dump($combinations);
$count = count($combinations);

echo "Day 17.1: $count\n";

$count = 0;
foreach ($combinations as $combination) {
    if (count($combination) === $minCombinationSize) {
        $count++;
    }
}

echo "Day 17.2: $count\n";
