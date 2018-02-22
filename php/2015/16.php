<?php
// http://adventofcode.com/2015/day/16

$resource = fopen("16_input.txt", "r");
$matches = [];
$aunts = [];

while (($line = fgets($resource)) !== false) {
    preg_match("/^Sue ([0-9]+): (.*)/", $line, $matches);

    $auntData = [];
    $properties = explode(", ", $matches[2]);
    foreach ($properties as $prop) {
        // $prop is for instance "cars: 9"
        $tmp = explode(": ", $prop);
        $auntData[$tmp[0]] = (int)$tmp[1];
    }
    $aunts[$matches[1]] = $auntData;
}

$scanResult = [
   "children" => 3,
   "cats" => 7,
   "samoyeds" => 2,
   "pomeranians" => 3,
   "akitas" => 0,
   "vizslas" => 0,
   "goldfish" => 5,
   "trees" => 3,
   "cars" => 2,
   "perfumes" => 1,
];

// for each sue
    // for of its properties
        // compare it with the result
            // discard if one of the properties is not right

$probableAunts = []; // hopefully it will only be filled with one value
foreach ($aunts as $id => $aunt) {
    foreach ($aunt as $prop => $value) {
        if ($value !== $scanResult[$prop]) {
            continue 2;
        }
    }

    // because of the continue 2, this is not reached if any of the aunt's properties hadn't the right value
    $probableAunts[$id] = $aunt;
}

$part2ProbableAunts = [];
foreach ($aunts as $id => $aunt) {
    $i = 0;
    foreach ($aunt as $prop => $value) {
        // check if that aunt's value is NOT right
        if ($prop === "cats" || $prop === "trees") {
            if ($value <= $scanResult[$prop]) {
                continue 2;
            }
        } elseif ($prop === "pomeranians" || $prop === "goldfish") {
            if ($value >= $scanResult[$prop]) {
                continue 2;
            }
        } elseif ($value !== $scanResult[$prop]) {
            continue 2;
        }
    }

    // because of the continue 2, this is not reached if any of the aunt's properties hadn't the right value
    $part2ProbableAunts[$id] = $aunt;
}

$id = array_keys($probableAunts)[0];
echo "Day 16.1: $id\n";

$id = array_keys($part2ProbableAunts)[0];
echo "Day 16.2: $id\n";
