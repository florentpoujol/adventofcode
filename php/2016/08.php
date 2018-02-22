<?php
// http://adventofcode.com/2016/day/8

$res = fopen("08_input.txt", "r");

$row = str_repeat(".", 50);
$screen = array_fill(0, 6, $row);
// $screen = [str_repeat(".", 7), str_repeat(".", 7), str_repeat(".", 7)];
$height = count($screen);
$width = strlen($screen[0]);

$matches = [];

while (($line = fgets($res)) !== false) {
    if (preg_match("/^rect ([0-9]+)x([0-9]+)$/", $line, $matches)) {
        for ($x = 0; $x < (int)$matches[1]; $x++) {
            for ($y = 0; $y < (int)$matches[2]; $y++) {
                $screen[$y][$x] = "#";
            }
        }
    }
    elseif (preg_match("/^rotate column x=([0-9]+) by ([0-9]+)$/", $line, $matches)) {
        $x = (int)$matches[1];
        $count = (int)$matches[2];

        $oldColumn = [];
        for ($y = 0; $y < $height; $y++) {
            $oldColumn[$y] = $screen[$y][$x];
        }

        for ($y = 0; $y < $height; $y++) {
            $oldKey = $y-$count;
            if ($oldKey < 0) {
                $oldKey += $height;
            }
            $screen[$y][$x] = $oldColumn[$oldKey];
        }

    }
    elseif (preg_match("/^rotate row y=([0-9]+) by ([0-9]+)$/", $line, $matches)) {
        $y = (int)$matches[1];
        $count = (int)$matches[2];

        $row = str_split($screen[$y]);
        $newRow = [];
        foreach ($row as $id => $cell) {
            $newId = ($id + $count) % $width;
            $newRow[$newId] = $cell;
        }
        ksort($newRow);
        $screen[$y] = implode("", $newRow);
    }
}

$count = 0;
foreach ($screen as $line) {
    $count += substr_count($line, "#");
}

echo "day 8.1: $count <br>\n";

// print screen

foreach ($screen as $row) {
    echo str_replace(".", " ", $row) . "\n";
}

echo "day 8.2: ZJHRKCPLYJ \n";
