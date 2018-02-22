<?php
// http://adventofcode.com/2016/day/17

ini_set('xdebug.max_nesting_level', 1000);

$passcode = "vwbaicqe";
// $passcode = "ihgpwlah"; // DDRRRD
// $passcode = "kglvqrro"; // DDUDRLRRUDRD
// $passcode = "ulqzkmiv"; // DRURDRUDDLLDLUURRDULRLDUUDDDRR

$grid = explode("\n", file_get_contents("17_room.txt"));
foreach ($grid as &$row) {
    $row = str_split($row);
}

$directions = ["U", "D", "L", "R"];
$coordVariationsPerDirection = [
    "U" => [0, -1],
    "D" => [0, 1],
    "L" => [-1, 0],
    "R" => [1, 0],
];

$openLetters = ["b", "c", "d", "e", "f"];

$shortestPaths = [];
$shortestPathLength = 99999;

$longestPathLength = -1;

function run(string $path, array $coords, int $callId)
{
    global $passcode, $openLetters, $debug, $directions, $grid, $coordVariationsPerDirection;

    if ($callId > 900) { // just a security to prevent the max recursion limit
        return;
    }

    $hash = substr(md5($passcode . $path), 0, 4);
    if ($debug) {
        echo "$callId   hash: $hash    path: $path\n";
    }

    $openDoors = [];
    foreach ($directions as $id => $direction) {
        if (in_array($hash[$id], $openLetters)) {
            // check that the direction does not lead in a wall
            $coordVar = $coordVariationsPerDirection[$direction];
            if ($grid[$coords[1] + $coordVar[1]][$coords[0] + $coordVar[0]] !== "#") {
                $openDoors[] = $direction;
            }
        }
    }

    if ($debug) {
        echo "$callId   open doors: " . implode("", $openDoors) . "\n";
    }

    if (empty($openDoors)) {
        // dead end
        return;
    }

    foreach ($openDoors as $direction) {
        $newPath = $path . $direction;

        $newCoords = $coords;
        $coordVar = $coordVariationsPerDirection[$direction];
        $newCoords[0] += $coordVar[0] * 2;
        $newCoords[1] += $coordVar[1] * 2;

        if ($debug) {
            echo "$callId   new coords: $newCoords[0], $newCoords[1]     new path: $newPath\n";
        }

        if ($newCoords[0] === 7 && $newCoords[1] === 7) {
            registerCompletedPath($newPath, $callId);
        } else {
            run($newPath, $newCoords,$callId + 1);
        }
    }
}

function registerCompletedPath(string $path, $callId)
{
    global $shortestPathLength, $shortestPaths, $longestPathLength, $debug;

    $pathLength = strlen($path);
    if ($pathLength < $shortestPathLength) {
        $shortestPathLength = $pathLength;
        $shortestPaths = [$path];
        if ($debug) {
            echo "$callId   shorter path ($shortestPathLength) $path \n";
        }
    } elseif ($pathLength === $shortestPathLength) {
        $shortestPaths[] = $path;
        if ($debug) {
            echo "$callId   shortest path ($shortestPathLength) $path \n";
        }
    }
    elseif ($pathLength > $longestPathLength) {
        $longestPathLength = $pathLength;
        if ($debug) {
            echo "$callId   longer path ($longestPathLength) $path \n";
        }
    } elseif ($pathLength === $longestPathLength) {
        if ($debug) {
            echo "$callId   longest path ($longestPathLength) $path \n";
        }
    }
}

$debug = false;

run("", [1, 1], 0);

$shortestPaths = array_unique($shortestPaths);
$path = $shortestPaths[0] ?? "no path";
// var_dump($shortestPaths);

echo "Day 17.1: $path ($shortestPathLength)\n";

echo "Day 17.2: $longestPathLength\n";
