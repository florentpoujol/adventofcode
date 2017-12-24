<?php
// http://adventofcode.com/2015/day/12

$input = file_get_contents("12_input.txt", "r");

$sum = 0;

$matches = [];
preg_match_all("/[0-9-]+/", $input, $matches);
foreach ($matches[0] as $value) {
    $sum += (int)$value;
}

echo "Day 12.1: $sum <br>";

// part 2

function explore_array($a)
{
    global $sum;

    foreach ($a as $i => $value) {
        $type = gettype($value);

        if ($type === "object") {
            explore_object($value);
        } elseif ($type === "array") {
            explore_array($value);
        } elseif ($type === "integer") {
            $sum += $value;
        }
    }
}

function explore_object($o)
{
    global $sum;

    $properties = get_object_vars($o);
    $values = array_values($properties);
    if (! in_array("red", $values, true)) { // true is needed here but not sure why. Some object must be equal to "red" ??
        foreach ($properties as $key => $value) {
            $type = gettype($value);

            if ($type === "object") {
                explore_object($value);
            } elseif ($type === "array") {
                explore_array($value);
            } elseif ($type === "integer"){
                $sum += $value;
            }
        }
    }
}

$sum = 0;
$data = json_decode($input);
explore_array($data);

echo "Day 12.2: $sum <br>";
