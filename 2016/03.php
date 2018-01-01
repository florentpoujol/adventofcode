<?php
// http://adventofcode.com/2016/day/3

$resource = fopen("03_input.txt", "r");

$triangles = [];

$matches = [];
while (($line = fgets($resource)) !== false) {
    preg_match("/^([0-9]+)( +)([0-9]+)( +)([0-9]+)$/", trim($line), $matches);
    $triangles[] = [(int)$matches[1], (int)$matches[3], (int)$matches[5]];
}

$validTrianglesCount = 0;
foreach ($triangles as $triangle) {
    sort($triangle);

    if ($triangle[0] + $triangle[1] > $triangle[2]) {
        $validTrianglesCount++;
    }
}

echo "Day 03.1: $validTrianglesCount\n";

$threeTriangles = [[], [], []];
$validTrianglesCount = 0;
foreach ($triangles as $id => $triangle) {
    $threeTriangles[0][] = $triangle[0];
    $threeTriangles[1][] = $triangle[1];
    $threeTriangles[2][] = $triangle[2];

    if (($id + 1) % 3 === 0) {
        foreach ($threeTriangles as $_triangle) {
            sort($_triangle);

            if ($_triangle[0] + $_triangle[1] > $_triangle[2]) {
                $validTrianglesCount++;
            }
        }
        $threeTriangles = [[], [], []];
    }
}

echo "Day 03.2: $validTrianglesCount\n";
