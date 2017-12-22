<?php
// http://adventofcode.com/2017/day/22

$test = "";
// $test = "_test"; // test
$resource = fopen("22_input$test.txt", "r");

$originalGrid = [];
while (($line = fgets($resource)) !== false) {
    $originalGrid[] = str_split(trim($line));
}

$grid = $originalGrid; // for part 1

// add 10 rows and columns to the existing grid
function expandGrid()
{
    global $grid, $gridSize;

    $a = str_split(str_repeat(".", 5));
    foreach ($grid as &$row) {
        $row = array_merge($a, $row, $a);
    }

    $rowSize = count($grid[0]);
    $a = str_split(str_repeat(".", $rowSize));
    array_unshift($grid, $a);
    array_unshift($grid, $a);
    array_unshift($grid, $a);
    array_unshift($grid, $a);
    array_unshift($grid, $a);
    $grid[] = $a;
    $grid[] = $a;
    $grid[] = $a;
    $grid[] = $a;
    $grid[] = $a;

    $gridSize = count($grid);
}

$gridSize = count($originalGrid);
$middleId = (int)($gridSize / 2);
$direction = [0, -1]; // up
$position = [$middleId, $middleId];
$infectionsCount = 0;

function turn($dir)
{
    global $direction;
    // $_dir = $direction[0] . "_" . $direction
    if ($dir === "left") { // counterclockwise
        switch ($direction) {
            case [0, -1]: // up
                $direction = [-1, 0]; // left
                break;
            case [-1, 0]: // left
                $direction = [0, 1]; // down
                break;
            case [0, 1]: // down
                $direction = [1, 0]; // right
                break;
            case [1, 0]: // right
                $direction = [0, -1]; // up
                break;
            default:
                var_dump($direction);
                exit("error, unknow direction.");
                break;
        }
    } elseif ($dir === "right") {
        switch ($direction) {
            case [0, -1]: // up
                $direction = [1, 0]; // right
                break;
            case [-1, 0]: // left
                $direction = [0, -1]; // up
                break;
            case [0, 1]: // down
                $direction = [-1, 0]; // left
                break;
            case [1, 0]: // right
                $direction = [0, 1]; // down
                break;
            default:
                var_dump($direction);
                exit("error, unknow direction.");
                break;
        }
    } elseif ($dir === "reverse") {
        $direction[0] = 0 - $direction[0];
        $direction[1] = 0 - $direction[1];
    }
}

function printGrid()
{
    global $grid;
    foreach ($grid as $row) {
        echo implode("", $row) . "\n";
    }
}

for ($burstCount = 1; $burstCount <= 10000; $burstCount++) {
    $node = $grid[$position[1]][$position[0]];
    if ($node === ".") {
        $node = "#";
        turn("left");
        $infectionsCount++;
    } else {
        $node = ".";
        turn("right");
    }
    $grid[$position[1]][$position[0]] = $node;

    $position[0] += $direction[0];
    $position[1] += $direction[1];

    // if position out of grid: expand grid
    if ($position[0] < 0 || $position[0] >= $gridSize ||
        $position[1] < 0 || $position[1] >= $gridSize)
    {
        expandGrid();
        $position[0] += 5; // value to be changed based on own much the grid is expanded
        $position[1] += 5;
    }
    // printGrid();
    // echo "-------\n";
}

echo "Day 22.1: $infectionsCount\n";

// part 2

$grid = $originalGrid;
$gridSize = count($originalGrid);
$middleId = (int)($gridSize / 2);
$direction = [0, -1]; // up
$position = [$middleId, $middleId];
$infectionsCount = 0;

for ($burstCount = 1; $burstCount <= 10000000; $burstCount++) {
    $node = $grid[$position[1]][$position[0]];
    if ($node === ".") {
        $node = "w";
        turn("left");
    } elseif ($node === "w") {
        $node = "#";
        // no turn
        $infectionsCount++;
    } elseif ($node === "#") {
        $node = "f";
        turn("right");
    } elseif ($node === "f") { //
        $node = ".";
        turn("reverse");
    }
    $grid[$position[1]][$position[0]] = $node;

    $position[0] += $direction[0];
    $position[1] += $direction[1];

    // if position out of grid: expand grid
    if ($position[0] < 0 || $position[0] >= $gridSize ||
        $position[1] < 0 || $position[1] >= $gridSize)
    {
        expandGrid();
        $position[0] += 5; // value to be changed based on own much the grid is expanded
        $position[1] += 5;
    }
    // printGrid();
    // echo "-------\n";
}

echo "Day 22.2: $infectionsCount\n";
