<?php
// http://adventofcode.com/2016/day/13

function getCell(int $x, int $y)
{
    global $input, $targetCoords;
    $decCell = $x*$x + 3*$x + 2*$x*$y + $y + $y*$y + $input;
    $binCell = decbin($decCell);
    $count = substr_count($binCell, "1");
    $cell = [
        "x" => $x,
        "y" => $y,
        "isWall" => $count % 2 !== 0,
        "distance" => abs($x - $targetCoords[0]) + abs($y - $targetCoords[1]), // manhattan distance
        "isVisited" => false
    ];
    return $cell;
}

function expandGrid(int $maxSize)
{
    global $grid, $gridSize;

    for ($y = 0; $y < $maxSize; $y++) {
        if (!isset($grid[$y])) {
            $grid[$y] = [];
        }
        for ($x = 0; $x < $maxSize; $x++) {
            if (!isset($grid[$y][$x])) {
                $grid[$y][$x] = getCell($x, $y);
            }
        }
    }
    $gridSize = count($grid);
}

function printGrid()
{
    global $grid, $visitedCoordinates, $targetCoords;

    foreach ($grid as $y => $row) {
        foreach ($row as $x => $cell) {
            $str = $cell["isWall"] ? "#": " ";
            if ($str === " ") {
                if ([$x, $y] == $targetCoords) {
                    $str = "T";
                } elseif (in_array([$x, $y], $visitedCoordinates)) {
                    $str = ".";
                } elseif ($cell["distance"] <= 9) {
                    $str = $cell["distance"];
                }
            }
            echo $str;
        }
        echo "\n";
    }
}

$input = 1352; $targetCoords = [31, 39];
// $input = 10; $targetCoords = [7, 4]; // test
$currentCoords = [1, 1];
$visitedCoordinates = [];
$grid = [];
$gridSize = 0;

expandGrid(50);


function findTargetNode()
{
    // get min steps from 1,1 to the target coords, using Dijkstra's algo
    // for each node, go toward the one that has the smallest distance and mark it as visited
    // if in a dead-end, just backtrack until you find the next non-visited neighbour

    global $grid, $gridSize, $currentCoords, $visitedCoordinates;

    $visitedCoordinates[] = $currentCoords;

    $currentNode = $grid[$currentCoords[1]][$currentCoords[0]];
    $grid[$currentCoords[1]][$currentCoords[0]]["isVisited"] = true;
    // $currentNode = &();
    // $currentNode["isVisited"] = true;

    if ($currentNode["distance"] === 0) {
        return true;
    }

    $neighbours = []; // neighbours per distance

    $neighboursCoordsModifiers = [[0, -1], [0, 1], [-1, 0], [1, 0]];
    foreach ($neighboursCoordsModifiers as $modif) {
        $nx = $currentCoords[0] + $modif[0];
        $ny = $currentCoords[1] + $modif[1];

        if ($nx >= $gridSize || $ny >= $gridSize) {
            expandGrid($gridSize * 2);
        }

        if (
            $nx > 0 && $ny > 0 &&
            !$grid[$ny][$nx]["isWall"] &&
            !$grid[$ny][$nx]["isVisited"]
        ) {
            $dist = $grid[$ny][$nx]["distance"];
            if (!isset($neighbours[$dist])) {
                $neighbours[$dist] = [];
            }
            $neighbours[$dist][] = &$grid[$ny][$nx];
        }
    }

    if (empty($neighbours)) {
        // revert to previous coordinates
        array_pop($visitedCoordinates); // current coord
        $currentCoords = array_pop($visitedCoordinates); // previous coord
        return false;
    }

    ksort($neighbours);
    $minDist = array_keys($neighbours)[0];

    $closestNode = $neighbours[$minDist][0];
    $currentCoords = [$closestNode["x"], $closestNode["y"]];
    return false;
}

$i = 0;
while ($i++ < 999 && !findTargetNode()) {}

$steps = count($visitedCoordinates) - 1;
echo "Day 13.1: $steps ($i)\n";

printGrid();

// part 2

