<?php
// http://adventofcode.com/2015/day/24

$weights = [1, 2, 3, 5, 7, 13, 17, 19, 23, 29, 31, 37, 41, 43, 53, 59, 61, 67, 71, 73, 79, 83, 89, 97, 101, 103, 107, 109, 113];
// $weights = [1, 2, 3, 4, 5, 7, 8, 9, 10, 11]; // test

// take last (biggest) number in the list
// compute remaining sum needed
// if not present in the list, take the next biggest number available that don't go over
// repeat
// if no number available, discard

function run(int $sumPerGroup, array $weights)
{
    // $remainingSum = array_sum($weights) / $groupCount; // sum per group
    $remainingSum = $sumPerGroup;
    $group = [];
    while ($remainingSum > 0 && !empty($weights)) {
        if (in_array($remainingSum, $weights)) {
            $group[] = $remainingSum;
            break;
        }

        $num = array_pop($weights);

        if ($num > $remainingSum) {
            // discard this number and continue with the next, which is smaller
            continue;
        }

        $remainingSum -= $num;
        $group[] = $num;
    }

    return $group;
}
// this (hopefully) find the first smallest group
// hopefully, there no other configuration for a group of that size...

$group = run(array_sum($weights) / 3, $weights);
$groupCount = count($group);
$qe = 1;
foreach ($group as $num) {
    $qe *= $num;
}

echo "Day 24.1: $qe ($groupCount)\n";

// the first group found for part 2 has 5 numbers (113, 109, 107, 53 and 2) but not the smallest QE (139699414)
// so try other grouping by removing some of the weights (keep 113)

$minQE = INF;

// $group = run(4, $weights);
// $groupCount = count($group); // 5

function getQE(array $group)
{
    $qe = 1;
    foreach ($group as $num) {
        $qe *= $num;
    }
    return $qe;
}

$sumPerGroup = array_sum($weights) / 4;
$minGroupCount = 4;
do {
    $group = run($sumPerGroup, $weights);
    array_splice($weights, -3, 1); // remove the second-to-last number (leave

    if (count($group) < $minGroupCount && array_sum($group) === $sumPerGroup) {
        $minGroupCount = count($group);
        var_dump("min group count:", $group);
    }

    if (count($group) !== $minGroupCount || array_sum($group) !== $sumPerGroup) {
        continue;
    }

    $qe = getQE($group);
    if ($qe < $minQE) {
        var_dump("min qe:", $group);
        $minQE = $qe;
    }
} while(!empty($weights));

echo "Day 24.2: $minQE\n"; // answer: 74850409 (group: 113, 109, 103, 59)
