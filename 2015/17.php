<?php
// http://adventofcode.com/2015/day/17

$input = [150, 43, 3, 4, 10, 21, 44, 4, 6, 47, 41, 34, 17, 17, 44, 36, 31, 46, 9, 27, 38];
$testInput = [25, 20, 15, 10, 5, 5];

$input = $testInput; // de-comment for test
$targetCapacity = array_shift($input); // 150, or 25 for test input
rsort($input);
var_dump($input);

// remove duplicates, but count them
$duplicates = []; // key = number, value = count

$i = 1;
do {
    $value = $input[$i];
    if ($value === $input[$i - 1]) {
        if (! isset($duplicates[$value])) {
            $duplicates[$value] = 1;
        }
        $duplicates[$value]++;
        //array_splice($input, $i, 1);
    }
    $i++;
} while (isset($input[$i]));
var_dump($input);
var_dump($duplicates);

// take the first number from input
// add it to total
// if total is inferior to 150 > repeat from the remaining entries
// if total if equal to 150
    // > save this configuration
    // > continue with remaining numbers if any
// if total is superior to 150 > discard this number and continue with remaining numbers

$combinations = [];
var_dump("-------------------------------------------------------");

function get_first_elt(&$array)
{
    foreach ($array as $id => $value) {
        $elt = ["id" => $id, "value" => $value, "str" => "$id $value"];
        unset($array[$id]);
        return $elt;
    }
    return null;
}

function process($combArray, $remainingArray)
{
    global $combinations, $targetCapacity;

    $sum = $combArray["sum"];
    $firstElt = get_first_elt($remainingArray);
    $newValue = $firstElt["value"];
    $newSum = $sum + $newValue;

    if ($newSum < $targetCapacity) {
        $combArray["sum"] = $newSum;
        $combArray[$firstElt["id"]] = $firstElt["value"];

        if (count($remainingArray) > 0) {
            process($combArray, $remainingArray);
        } else {
            var_dump($combArray);
        }

    } elseif ($newSum === $targetCapacity) {
        //process($combArray, $remainingArray);
        $oldCombArray = $combArray;

        $combArray["sum"] = $newSum;
        $combArray[$firstElt["id"]] = $firstElt["value"];
        $combinations[] = $combArray;

        if (count($remainingArray) > 0) {
            // continue with the old combArray and the "winning value" removed
            process($oldCombArray, $remainingArray);
        } else {
            var_dump("=== SUCCESS ===");
            var_dump($combArray);
        }

    } elseif ($newSum > $targetCapacity) {
        // if remaingArray isn't empty
        // continue but the value that put the sum above the target removed
        if (count($remainingArray) > 0) {
            process($combArray, $remainingArray);
        } else {
            var_dump($combArray);
        }
    }
}

while (($initialValue = get_first_elt($input)) !== null) {
    $combArray = [
        "sum" => $initialValue["value"],
        $initialValue["id"] => $initialValue["value"],
    ];
    process($combArray, $input);
}
var_dump("-------------------------------------------------------");

var_dump($combinations); // 3, 6 = too low

// loop through the combinations
// if one of the number part of that combination is one of the duplicates
// increase the resultCount by that occurence count

$resultCount = 0;
$duplicatesValues = array_keys($duplicates);
foreach ($combinations as $combination) {
    $count = 1;
    foreach ($combination as $key => $value) {
        if ($key === "sum") {
            continue;
        }

        if (in_array($value, $duplicatesValues)) {
            $count *= $duplicates[$value];
        }
    }
    $resultCount += $count;
}


var_dump("resultCount = $resultCount");


