<?php
// http://adventofcode.com/2015/day/18
// game of life
$test = "";
$steps = 100;
// $test = "_test"; $steps = 5; // test
$resource = fopen("18_input$test.txt", "r");

$grid = [];
while (($line = fgets($resource)) !== false) {
    $grid[] = str_split(trim($line));
}

$neighboursDirections = [
    [-1, -1], // top left
    [0, 1], [0, 1],
    [1, 0], [1, 0],
    [0, -1], [0, -1],
    [-1, 0],
];

function run(bool $part2 = false)
{
    global $grid, $steps, $neighboursDirections;

    $lastId = count($grid) - 1;

    for ($i = 0; $i < $steps; $i++) {
        // printGrid();
        // echo "----------------\n";

        $oldGrid = $grid;

        foreach ($oldGrid as $y => $row) {
            foreach ($row as $x => $cell) {
                if (
                    $part2 &&
                    (
                        ($x === 0 && $y === 0) ||
                        ($x === 0 && $y === $lastId) ||
                        ($x === $lastId && $y === 0) ||
                        ($x === $lastId && $y === $lastId)
                    )
                ) {
                    // part and on a corner
                    // just skip, let the cell be lit
                    continue;
                }

                $onNeighbours = 0;
                $neighbourPosition = [$x, $y];
                foreach ($neighboursDirections as $direction) {
                    $neighbourPosition[0] += $direction[0];
                    $neighbourPosition[1] += $direction[1];
                    $nx = $neighbourPosition[0];
                    $ny = $neighbourPosition[1];

                    if (isset($oldGrid[$ny]) && isset($oldGrid[$ny][$nx]) && $oldGrid[$ny][$nx] === "#") {
                        $onNeighbours++;
                    }
                }

                if ($cell === "#" && $onNeighbours !== 2 && $onNeighbours !== 3) {
                    $grid[$y][$x] = ".";
                } elseif ($cell === "." && $onNeighbours === 3) {
                    $grid[$y][$x] = "#";
                }
            }
        }
    }
}

// printGrid();
// echo "----------------\n";

function printGrid()
{
    global $grid;
    foreach ($grid as $row) {
        echo implode("", $row) . "\n";
    }
}

$originalGrid = $grid; // for part 2
run();

$litCount = 0;
foreach ($grid as $row) {
    $values = array_count_values($row);
    $litCount += $values["#"] ?? 0;
}

echo "Day 18.1: $litCount\n";

// part 2

$grid = $originalGrid;
$lastId = count($grid) - 1;
$grid[0][0] = "#";
$grid[0][$lastId] = "#";
$grid[$lastId][0] = "#";
$grid[$lastId][$lastId] = "#";
run(true);

$litCount = 0;
foreach ($grid as $row) {
    $values = array_count_values($row);
    $litCount += $values["#"] ?? 0;
}

echo "Day 18.2: $litCount\n";
