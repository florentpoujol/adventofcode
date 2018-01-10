<?php
// https://adventofcode.com/2016/day/18

$row = ".^^..^...^..^^.^^^.^^^.^^^^^^.^.^^^^.^^.^^^^^^.^...^......^...^^^..^^^.....^^^^^^^^^....^^...^^^^..^";
$height = 40;
// $row = ".^^.^.^^^^"; // test

$row = str_split($row);
$width = count($row);

$grid = [$row];
function printGrid()
{
    global $grid;
    foreach ($grid as $row) {
        echo implode("", $row) . "\n";
    }
}

$tileCount = 0;

function run()
{
    global $grid, $height, $width, $tileCount;

    $tileCount .= array_count_values($grid[0])["."];

    for ($y = 1; $y < $height; $y++) {
        $row = [];
        $previousRow = $grid[$y - 1];
        for ($x = 0; $x < $width; $x++) {
            $left = $previousRow[$x - 1] ?? ".";
            $right = $previousRow[$x + 1] ?? ".";

            $tile = "^";
            if ($left === $right) {
                $tile = ".";
                $tileCount++;
            }
            $row[] = $tile;
        }
        $grid[] = $row;
    }
}

// printGrid();

run();
echo "Day 18.1: $tileCount\n";

// part 2

$row = ".^^..^...^..^^.^^^.^^^.^^^^^^.^.^^^^.^^.^^^^^^.^...^......^...^^^..^^^.....^^^^^^^^^....^^...^^^^..^";
$height = 400000;
$grid = [str_split($row)];
$tileCount = 0;

run();
echo "Day 18.2: $tileCount\n";
