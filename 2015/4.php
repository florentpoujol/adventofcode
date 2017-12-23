<?php
// http://adventofcode.com/2015/day/4

$input = "bgvyzdsv";
$searchedId = 0;
$searchedId2 = 0;
$i = 0;

while ($searchedId === 0 || $searchedId2 === 0) {
    $i++;
    $str = $input.$i;
    $hash = md5($str);

    if (substr($hash, 0, 5) === "00000") {
        $searchedId = $i;
    }

    if (substr($hash, 0, 6) === "000000") {
        $searchedId2 = $i;
    }
}

echo "day 4.1: $searchedId <br>";
echo "day 4.2: $searchedId2 <br>";
