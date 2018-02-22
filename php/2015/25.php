<?php
// http://adventofcode.com/2015/day/25

// we don't really care about the grid of code, we just need to know
// how many codes are there before the one we want

// if we fill a grid of code, starting at 1 and increasing by one every time
// the algo to find any number is:   previousNumInThatRow + rowId + colId - 1
// so we can just build the target row until we reach the target column
// the first previous value of a row can be found on the first row, at (colId = rowId -  1)

// VALUES FROM PUZZLE INPUT
// x = 3029 y = 2947
$targetCoords = [3029, 2947]; // puzzle input
// $targetCoords = [3, 4]; // test

// get the value of the first row at colId = 3028

$value = 0;
for ($x = 1; $x <= $targetCoords[1] - 1; $x++) {
    $value = $value + $x;
}

// get targetRow
$value++; // value is now the value at x = 1, y = 3029
for ($x = 2; $x <= $targetCoords[0]; $x++) {
    $value = $value + $x + $targetCoords[1] - 1; // 3028 = rowId - 1
}
// value represents how many time we have to process our starting code to get the one we want

$code = 20151125; // given by the puzzle instruction

// $value = 21; // test
for ($i = 2; $i <= $value; $i++) {
    $code = ($code * 252533) % 33554393;
}

echo "Day 25.1: $code\n";
