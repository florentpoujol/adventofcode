<?php
// http://adventofcode.com/2016/day/2

$lines = explode("\n", file_get_contents("02_input.txt"));
// $lines = ["ULL", "RRDDD", "LURDL", "UUUUD"]; // test 1985

$keypad = [
    [1, 2, 3],
    [4, 5, 6],
    [7, 8, 9]
];

$row = 1;
$col = 1;

$code = "";

foreach ($lines as $line) {
    $directions = str_split($line);

    foreach ($directions as $direction) {
        switch ($direction) {
            case "U":
                if ($row !== 0) {
                    $row--;
                }
                break;
            case "D":
                if ($row !== 2) {
                    $row++;
                }
                break;
            case "L":
                if ($col !== 0) {
                    $col--;
                }
                break;
            case "R":
                if ($col !== 2) {
                    $col++;
                }
                break;
        }
    }

    $code .= $keypad[$row][$col];
}

echo "Day 02.1: $code\n";

// part 2

$keypad = [
    [0, 0, 1, 0, 0],
    [0, 2, 3, 4, 0],
    [5, 6, 7, 8, 9],
    [0, "A", "B", "C", 0],
    [0, 0, "D", 0, 0]
];

$row = 2;
$col = 0;

$code = "";

function isValidPosition($position, $varName)
{
    global $keypad, $row, $col;
    $_row = $row;
    $_col = $col;

    if ($position < 0 || $position > 4) {
        return false;
    }

    ${"_$varName"} = $position;
    if ($keypad[$_row][$_col] === 0) {
        return false;
    }

    return true;
}

foreach ($lines as $line) {
    $directions = str_split($line);

    foreach ($directions as $direction) {
        switch ($direction) {
            case "U":
                if (isValidPosition($row-1, "row")) {
                    $row--;
                }
                break;
            case "D":
                if (isValidPosition($row+1, "row")) {
                    $row++;
                }
                break;
            case "L":
                if (isValidPosition($col-1, "col")) {
                    $col--;
                }
                break;
            case "R":
                if (isValidPosition($col+1, "col")) {
                    $col++;
                }
                break;
        }
    }

    $code .= $keypad[$row][$col];
}

echo "Day 02.2: $code\n";
