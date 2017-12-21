<?php
// http://adventofcode.com/2017/day/21

$test = "";
// $test = "_test"; // test
$resource = fopen("21_input$test.txt", "r");
$rules = [];
while (($line = fgets($resource)) !== false) {
    $parts = explode(" => ", trim($line));

    $input = explode("/", $parts[0]);
    foreach ($input as &$item) {
        $item = str_split($item);
    }

    $output = explode("/", $parts[1]);
    foreach ($output as &$item) {
        $item = str_split($item);
    }

    $rules[] = [
        "size" => count($input),
        "input" => $input,
        "output" => $output,
    ];
}


// flip a square vertically
function flipSquare(array $grid): array
{
    foreach ($grid as &$row) {
        $row = array_reverse($row);
    }
    return $grid;
}

// rotate a square 90° clockwise
function rotateSquare(array $grid): array
{
    $newGrid = [];
    $size = count($grid);
    for ($col = 0; $col < $size; $col++) {
        $newGrid[] = [];
        for ($row = $size - 1; $row >= 0; $row--) {
            $newGrid[$col][] = $grid[$row][$col];
        }
    }
    return $newGrid;
}

// compare two squares (returns true if they match)
function compareSquare(array $grid1, array $grid2): bool
{
    $size = count($grid1);
    if ($size !== count($grid2)) {
        return false;
    }
    for ($i = 0; $i < $size; $i++) {
        if ($grid1[$i] !== $grid2[$i]) {
            return false;
        }
    }
    return true;
}

function breakDownGrid(int $squareSize, int $squareCountPerSide): array
{
    global $grid;
    if ($squareCountPerSide === 1) {
        return [$grid];
    }

    $squares = [];
    $gridSize = count($grid);

    $_grid = [];
    for ($i = 0; $i < $gridSize; $i++) {
        $_grid[] = array_chunk($grid[$i], $squareSize);

        if (($i + 1) % $squareSize === 0) {
            for ($colId = 0; $colId < $squareCountPerSide; $colId++) {
                $square = [];
                for ($rowId = 0; $rowId < $squareSize; $rowId++) {
                    $square[] = $_grid[$rowId][$colId];
                }
                $squares[] = $square;
            }
            $_grid = [];
        }
    }

    return $squares;
}

function assembleGrid(array $squares, int $squareSize, int $squareCountPerSide)
{
    global $grid;

    $grid = [];
    $squaresPerGridRow = array_chunk($squares, $squareCountPerSide);
    foreach ($squaresPerGridRow as $gridRowSquares) {
        for ($squareRowId = 0; $squareRowId < $squareSize; $squareRowId++) {
            $gridRow = [];
            foreach ($gridRowSquares as $gridRowSquare) {
                $gridRow = array_merge($gridRow,  $gridRowSquare[$squareRowId]);
            }
            $grid[] = $gridRow;
        }
    }
}

function printGrid($grid)
{
    echo "----------\n";
    foreach ($grid as $row) {
        echo implode("", $row) . "\n";
    }
    echo "----------\n";
}

//

$grid = [
    [".", "#", "."],
    [".", ".", "#"],
    ["#", "#", "#"],
];

$iterations = 5; // part 1
$iterations = 18; // part 2 (takes 30 seconds o compute on my computer)
// $iterations = 2; // test

for ($it = 0; $it < $iterations; $it++) {
    // echo "==== Start iteration $it ===\n";
    // printGrid($grid);
    $gridSize = count($grid);

    $squareSize = 3;
    if ($gridSize % 2 === 0) {
        $squareSize = 2;
    }
    $squareCountPerSide = $gridSize / $squareSize;

    $squares = breakDownGrid($squareSize, $squareCountPerSide);
    // echo "== printSquares ==\n";


    // loop on all the squares, compare them to every rules then apply the matching one
    foreach ($squares as &$square) {
        // printGrid($square);

        $allSquares = []; // all versions of the given square rotated and flipped

        $s = $square;
        for ($i = 0; $i < 4; $i++) {
            $allSquares[] = $s;
            $allSquares[] = flipSquare($s);
            $s = rotateSquare($s);
        }

        foreach ($rules as $rule) {
            if ($rule["size"] !== $squareSize) {
                continue;
            }
            $input = $rule["input"];

            foreach ($allSquares as $_square) {
                if (compareSquare($_square, $input)) {
                    $square = $rule["output"]; // replace the square (by reference) by the output
                    // echo "ouput: \n";
                    // printGrid($square);
                    break(2);
                }
            }

            // exit("error, this part should be unreachable");
        }
    }
    // echo "=========== /END printSquares ============\n";


    assembleGrid($squares, $squareSize + 1, $squareCountPerSide);
    // printGrid($grid);
    // echo "==== end iteration $it ===\n";
}

// count on pixels
$litCount = 0;
foreach ($grid as $row) {
    $row = implode("", $row);
    $row = str_replace(".", "", $row);
    $litCount += strlen($row);
}

echo "Day 21 (part 1 or 2, see code): $litCount\n";
