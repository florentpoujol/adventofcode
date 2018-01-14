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
