<?php
// http://adventofcode.com/2017/day/14

ob_start();
require_once "10.php"; // for function knotHash()
ob_clean();

$grid = []; // array of bits strings
$key = "amgozmfv";
//$key = "flqrgnkx"; // test
$quareUsed = 0;

function hexToBin(string $str): string
{
    $chars = str_split($str);
    $bin = "";
    foreach ($chars as $char) {
        $tmp = base_convert($char, 16, 2);
        $bin .= str_pad($tmp, 4, "0", STR_PAD_LEFT);
    }
    return $bin;
}

for ($i = 0; $i < 128; $i++) {
    $str = "$key-$i";
    $hash = knotHash($str); // see in 10.php
    $bin = hexToBin($hash);
    $quareUsed += strlen(str_replace("0", "", $bin));
    $grid[] = str_split($bin);
}

echo "Day 14.1: $quareUsed\n";

// part 2

$regionCount = 0;

function findRegion(int $y, int $x, bool $regionInProgress = false)
{
    global $grid, $regionCount;
    if ($grid[$y][$x] === "2") {
        return;
    }

    $squareIsUsed = ($grid[$y][$x] === "1");
    $grid[$y][$x] = "2"; // mark the square as "evaluated"

    if ($squareIsUsed) {
        // check the squares left, right, up and down
        if ($x > 0) {
            findRegion($y, $x - 1, true);
        }
        if ($x < 127) {
            findRegion($y, $x + 1, true);
        }
        if ($y > 0) {
            findRegion($y - 1, $x, true);
        }
        if ($y < 127) {
            findRegion($y + 1, $x, true);
        }

        // by this point, findRegion() has finished recursing, so we found a whole region
        if (! $regionInProgress) {
            $regionCount++;
        }
    }
}

for ($y = 0; $y < 128; $y++) {
    for ($x = 0; $x < 128; $x++) {
        findRegion($y, $x);
        // there is no need to call the function on each and every squares
        // after each region, it should only be called on the next unevaluated square
    }
}

echo "Day 14.2: $regionCount\n";
