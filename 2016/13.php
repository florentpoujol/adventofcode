<?php
// http://adventofcode.com/2016/day/13

class Common
{
    public $input = 0;

    public $grid = [];

    public $gridSize = 0;

    public $visitedCoordinates = [];

    public $currentCoords = [1, 1];

    public $targetCoords = [];

    public function __construct(int $input, array $targetCoords, int $gridSize)
    {
        $this->input = $input;
        $this->targetCoords = $targetCoords;
        $this->gridSize = $gridSize;
        $this->expandGrid($gridSize);
    }

    function expandGrid(int $maxSize)
    {
        $this->gridSize = $maxSize;
        for ($y = 0; $y < $maxSize; $y++) {
            if (!isset($this->grid[$y])) {
                $this->grid[$y] = [];
            }
            for ($x = 0; $x < $maxSize; $x++) {
                if (!isset($this->grid[$y][$x])) {
                    $this->grid[$y][$x] = $this->getCell($x, $y);
                }
            }
        }
    }

    public function getCell(int $x, int $y)
    {
        $decCell = $x*$x + 3*$x + 2*$x*$y + $y + $y*$y + $this->input;
        $binCell = decbin($decCell);
        $count = substr_count($binCell, "1");
        $cell = [
            "x" => $x,
            "y" => $y,
            "isWall" => $count % 2 !== 0,
            "distance" => abs($x - $this->targetCoords[0]) + abs($y - $this->targetCoords[1]), // manhattan distance
            "isVisited" => false
        ];
        return $cell;
    }
}

class Part1 extends Common
{
    function printGrid()
    {
        foreach ($this->grid as $y => $row) {
            foreach ($row as $x => $cell) {
                $str = $cell["isWall"] ? "#": " ";
                if ($str === " ") {
                    if ([$x, $y] == $this->targetCoords) {
                        $str = "T";
                    } elseif (in_array([$x, $y], $this->visitedCoordinates)) {
                        $str = ".";
                    } elseif ($cell["distance"] <= 9) {
                        $str = $cell["distance"];
                    }
                }
                echo $str . " ";
            }
            echo "\n";
        }
    }

    function findTargetNode(): bool
    {
        // get min steps from 1,1 to the target coords, using Dijkstra's algo
        // for each node, go toward the one that has the smallest distance and mark it as visited
        // if in a dead-end, just backtrack until you find the next non-visited neighbour

        $this->visitedCoordinates[] = $this->currentCoords;

        $currentNode = &$this->grid[$this->currentCoords[1]][$this->currentCoords[0]];
        $currentNode["isVisited"] = true;
        $distance = $currentNode["distance"];
        unset($currentNode); // because of the passing by reference

        if ($distance === 0) {
            return true;
        }

        $neighbours = []; // neighbours per distance

        $neighboursCoordsModifiers = [[0, -1], [0, 1], [-1, 0], [1, 0]];
        foreach ($neighboursCoordsModifiers as $modif) {
            $nx = $this->currentCoords[0] + $modif[0];
            $ny = $this->currentCoords[1] + $modif[1];

            if ($nx >= $this->gridSize || $ny >= $this->gridSize) {
                $this->expandGrid($this->gridSize * 2);
            }

            if (
                $nx >= 0 && $ny >= 0 &&
                !$this->grid[$ny][$nx]["isWall"] &&
                !$this->grid[$ny][$nx]["isVisited"]
            ) {
                $dist = $this->grid[$ny][$nx]["distance"];
                if (!isset($neighbours[$dist])) {
                    $neighbours[$dist] = [];
                }
                $neighbours[$dist][] = $this->grid[$ny][$nx];
            }
        }

        if (empty($neighbours)) {
            // revert to previous coordinates
            array_pop($this->visitedCoordinates); // current coord
            $this->currentCoords = array_pop($this->visitedCoordinates); // previous coord

            if ($this->currentCoords === null) {
                // this happens when we have backtracked all the way to the starting position
                // with all its neighbours visited
                // or the target coord is unreachable
                return "unreachable";
            }
            return false;
        }

        ksort($neighbours);
        $minDist = array_keys($neighbours)[0];

        $closestNode = $neighbours[$minDist][0];
        $this->currentCoords = [$closestNode["x"], $closestNode["y"]];
        return false;
    }

    function getStepsToTargetCoords()
    {
        $i = 0;
        while ($i++ < 999 && !($result = $this->findTargetNode())) {}
        $count = count($this->visitedCoordinates) - 1;
        return $count;
    }
}



$part1 = new Part1(1352, [31, 39], 50);
// $part1 = new Part1(10, [7, 4], 30); // test

$steps = $part1->getStepsToTargetCoords();
echo "Day 13.1: $steps \n";
// $part1->printGrid();

// exit;
// part 2

// for part 2 we have to deal with unreachable tiles
// and we must no use Dijkstra's algo we must be able to visit all possible paths from the start

class Part2 extends Common
{
    public $stepsPerCoordinates = [];

    public $closeCoordinates = [];

    function printGrid()
    {
        foreach ($this->grid as $y => $row) {
            foreach ($row as $x => $cell) {
                $str = $cell["isWall"] ? "#": " ";
                if ($str === " ") {
                    // $id = array_search([$x, $y], $this->closeCoordinates);
                    // if ($id !== false) {
                    //     $str = $this->stepsPerCoordinates[$x . "_$y"];
                    // }
                    $strCoords = $x."_".$y;
                    $str = $this->stepsPerCoordinates[$strCoords] ?? " ";
                }
                if (strlen($str) === 1) {
                    $str .= " ";
                }
                echo $str;
            }
            echo "\n";
        }
    }

    function getActualStepsToTarget(array $targetCoords): int
    {
        $part1 = new Part1(1352, $targetCoords, 50);
        return $part1->getStepsToTargetCoords();
    }

    function findAllNodes()
    {
        $this->visitedCoordinates[] = $this->currentCoords;

        $actualSteps = $this->getActualStepsToTarget($this->currentCoords);
        if ($actualSteps === -1) {
            var_dump("-1 steps", $this->currentCoords);
            exit;
        }

        if (!in_array($this->currentCoords, $this->closeCoordinates) &&
            $actualSteps >= 0 && $actualSteps <= 50
        ) {
            $this->closeCoordinates[] = $this->currentCoords;

            $coord = $this->currentCoords[0] . "_" . $this->currentCoords[1];
            if (!isset($this->stepsPerCoordinates[$coord])) {
                $this->stepsPerCoordinates[$coord] = 60;
            }

            $this->stepsPerCoordinates[$coord] = min(
                $this->stepsPerCoordinates[$coord],
                // min($approximateSteps, $actualSteps)
                $actualSteps
            );
        }

        $currentNode = &$this->grid[$this->currentCoords[1]][$this->currentCoords[0]];
        $currentNode["isVisited"] = true;
        unset($currentNode); // because of the passing by reference

        $neighbours = []; // unvisited neighbours

        $neighboursCoordsModifiers = [[0, -1], [0, 1], [-1, 0], [1, 0]];
        foreach ($neighboursCoordsModifiers as $modif) {
            $nx = $this->currentCoords[0] + $modif[0];
            $ny = $this->currentCoords[1] + $modif[1];

            if (
                $nx >= 0 && $ny >= 0 &&
                $nx < $this->gridSize && $ny < $this->gridSize &&
                !$this->grid[$ny][$nx]["isWall"] &&
                !$this->grid[$ny][$nx]["isVisited"]
            ) {
                $neighbours[] = $this->grid[$ny][$nx];
            }
        }

        if (empty($neighbours)) {
            // revert to previous coordinates
            array_pop($this->visitedCoordinates); // current coord
            $this->currentCoords = array_pop($this->visitedCoordinates); // previous coord

            if ($this->currentCoords === null) {
                // this happens when we have exhausted all paths
                // and backtracked all the way to the starting position
                // with all its neighbours visited
                return true;
            }

            return false;
        }

        $chosenNeighbour = $neighbours[0];
        $this->currentCoords = [$chosenNeighbour["x"], $chosenNeighbour["y"]];
        return false;
    }

    function getAllNodesCount(): int
    {
        $i = 0;
        while ($i++ < 999 && !$this->findAllNodes()) {}

        return count($this->closeCoordinates);
    }

}


$part2 = new Part2(1352, [0, 0], 30);
$count = $part2->getAllNodesCount();
echo "Day 13.2: $count \n";
$part2->printGrid();
