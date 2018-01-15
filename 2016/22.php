<?php
// https://adventofcode.com/2016/day/22

$lines = explode("\n", file_get_contents("22_input.txt"));
$grid = [];
$matches = [];
foreach ($lines as $line) {
    preg_match("#^/dev/grid/node-x([0-9]+)-y([0-9]+)[ ]+([0-9]+)T[ ]+([0-9]+)T[ ]+([0-9]+)#", $line, $matches);

    $y = (int)$matches[2];
    if (!isset($grid[$y])) {
        $grid[$y] = [];
    }

    $x = (int)$matches[1];
    $grid[$y][$x] = [
        "size" => (int)$matches[3],
        "used" => (int)$matches[4],
        "available" => (int)$matches[5],
    ];
}

ksort($grid);
foreach ($grid as &$row) {
    ksort($row);
}
unset($row); // /!\ needed to remove the reference /!\

$viablePairCount = 0;
$pairs = [];

foreach ($grid as $y => $row) {
    foreach ($row as $x => $cell) {
        if ($cell["used"] <= 0) {
            continue;
        }
        $coords = $x . "_$y";

        foreach ($grid as $y2 => $row2) {
            foreach ($row2 as $x2 => $cell2) {
                $coords2 = $x2 . "_$y2";
                if ($cell === $cell2) {
                    continue;
                }

                if ($cell2["available"] >= $cell["used"]) {
                    $viablePairCount++;
                    $pairs[] = "$coords|$coords2";
                }
            }
        }
    }
}

// var_dump($pairs);

echo "Day 22.1: $viablePairCount\n"; // 903 too high

// part 2

$test = "";
//$test = "_test"; // test

if ($test !== "") {
    $lines = explode("\n", file_get_contents("22_input$test.txt"));
    $grid = [];
    $matches = [];
    foreach ($lines as $line) {
        preg_match("#^/dev/grid/node-x([0-9]+)-y([0-9]+)[ ]+([0-9]+)T[ ]+([0-9]+)T[ ]+([0-9]+)#", $line, $matches);

        $y = (int)$matches[2];
        if (!isset($grid[$y])) {
            $grid[$y] = [];
        }

        $x = (int)$matches[1];
        $grid[$y][$x] = [
            "size" => (int)$matches[3],
            "used" => (int)$matches[4],
            "available" => (int)$matches[5],
        ];

    }

    ksort($grid);
    foreach ($grid as &$row) {
        ksort($row);
    }
    unset($row); // /!\ needed to remove the reference /!\
}

$emptyNode = [];
foreach ($grid as $row) {
    foreach ($row as $cell) {
        if ($cell["used"] === 0) {
            $emptyNode = [$x, $y];
            break(2);
        }
    }
}
$targetNode = [0, count($grid[0]) - 1];

function printGrid()
{
    global $grid;
    $height = min(count($grid), 5);

    for ($y = 0; $y < $height; $y++) {
        $row = "";
        foreach ($grid[$y] as $cell) {
            $row .=
                str_pad($cell["used"], 3, " ", STR_PAD_LEFT) . " " .
                str_pad($cell["available"], 3, " ", STR_PAD_BOTH) . " " .
                str_pad($cell["size"], 3, " ") . "|";
        }
        echo "$row\n";
    }
}

// in the real input data, there is one empty node 24,22, with 86T of capacity

// the target node 31,0 contains 68T of data
// we first need to find the shortest route that this data can take (based on the nodes capacity)
// then we need to find the quickest way to "move a empty node" to the node just in front of the target data

// moving the empty node actually involve finding the neighbour in the correct direction
// that can empty in the currently empty node

// first find the shortest path from target to 0,0
// a peek at the actual data show that all nodes of the first row are big enough to hold the target data
// so it will just travel along that line


// to move the data around, the node in front of it just need to be empty

// so first check if we can do that without requiring the empty node
// just try to move any of the first row's data in the row below

printGrid();

function emptyNode(array $to)
{
    global $emptyNode;
    // make the empty node "travel" from its current position to the specified coordinates
}


function moveData(array $from, array $to)
{
    global $grid, $emptyNode;
    $fromNode = &$grid[$from[1]][$from[0]];
    $toNode = &$grid[$to[1]][$to[0]];

    if ($fromNode["used"] > $toNode["available"]) {
        var_dump("error, not enough space in to node", $from, $fromNode, $to, $toNode);
        exit;
    }

    $toNode["used"] += $fromNode["used"];
    $toNode["available"] -= $fromNode["used"];

    $fromNode["used"] = 0;
    $fromNode["available"] = $fromNode["size"];

    $emptyNode = $fromNode;
}

function moveEmptyNodeTo(array $to)
{

}

// return true when has successfully cleared the path forward the targetNode
/*function clearPathForward(): bool
{
    global $grid, $targetNode;
    // take the node forward the target node
    $x = $targetNode[0] - 1;
    $y = $targetNode[1];

    // try to empty it below at y=1
    if ($grid[$y - 1][$x]["available"] >= $grid[$y][$x]["used"]) {
        moveData([$x, $y], [$x, $y - 1]);
        return true;
    }

    // if not possible try to empty y=1 to y=2
    elseif ($grid[$y - 2][$x]["available"] >= $grid[$y - 1][$x]["used"]) {
        moveData([$x, $y - 1], [$x, $y - 2]);
        return clearPathForward();
    }

    // if not possible try to empty y=2 in one of its neighbour (except up)
    // left
    elseif (isset($grid[$y - 2][$x - 1]) && $grid[$y - 2][$x - 1]["available"] >= $grid[$y - 2][$x]["used"]) {
        moveData([$x, $y - 2], [$x - 1, $y - 2]);
        return clearPathForward();
    }
    // right
    elseif (isset($grid[$y - 2][$x + 1]) && $grid[$y - 2][$x + 1]["available"] >= $grid[$y - 2][$x]["used"]) {
        moveData([$x, $y - 2], [$x + 1, $y - 2]);
        return clearPathForward();
    }
    // bottom
    elseif (isset($grid[$y - 3]) && $grid[$y - 3][$x]["available"] >= $grid[$y - 2][$x]["used"]) {
        moveData([$x, $y - 2], [$x, $y - 3]);
        return clearPathForward();
    }
    return false;
}*/



