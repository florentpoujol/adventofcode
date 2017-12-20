<?php
// http://adventofcode.com/2017/day/20

$test = "";
// $test = "_test"; // test
$resource = fopen("20_input$test.txt", "r");
$particles = [];

while (($line = fgets($resource)) !== false) {
    $pattern = "/p=<([0-9-]+),([0-9-]+),([0-9-]+)>, v=<([0-9-]+),([0-9-]+),([0-9-]+)>, a=<([0-9-]+),([0-9-]+),([0-9-]+)>/";
    $matches = [];
    preg_match($pattern, $line, $matches);

    $particles[] = [
        "p" => [(int)$matches[1], (int)$matches[2], (int)$matches[3]],
        "v" => [(int)$matches[4], (int)$matches[5], (int)$matches[6]],
        "a" => [(int)$matches[7], (int)$matches[8], (int)$matches[9]],
        "destroyed" => false,
    ];
}


$closestParticleId = -1;
$ticks = 0; // ticks this particle has been the closest

$i = 0;
while ($i++ < 9999) {
    $ticks++;
    $tickClosestParticleId = -1;
    $tickSmallestDist = 99999;
    $particleIdsPerPosition = [];

    // update positions
    foreach ($particles as $id => &$particle) {
        $particle["v"][0] += $particle["a"][0];
        $particle["v"][1] += $particle["a"][1];
        $particle["v"][2] += $particle["a"][2];

        $particle["p"][0] += $particle["v"][0];
        $particle["p"][1] += $particle["v"][1];
        $particle["p"][2] += $particle["v"][2];

        // calculate distance to center
        $dist = abs($particle["p"][0]) + abs($particle["p"][1]) + abs($particle["p"][2]);
        if (!$particle["destroyed"] && $dist < $tickSmallestDist) {
            $tickSmallestDist = $dist;
            $tickClosestParticleId = $id;
        }

        if (!$particle["destroyed"]) {
            $pos = $particle["p"][0] . "_" . $particle["p"][1] . "_" . $particle["p"][2];
            if (!isset($particleIdsPerPosition[$pos])) {
                $particleIdsPerPosition[$pos] = [];
            }
            $particleIdsPerPosition[$pos][] = $id;
        }
    }

    foreach ($particleIdsPerPosition as $ids) {
        if (count($ids) > 1) {
            foreach ($ids as $id) {
                $particles[$id]["destroyed"] = true;
            }
        }
    }

    if ($tickClosestParticleId !== -1 && $tickClosestParticleId !== $closestParticleId) {
        // I actually don't get how $tickClosestParticleId can be === -1
        $closestParticleId = $tickClosestParticleId;
        $tick = 0;
    }

    if ($ticks > 999) {
        // this particle has been the closest to the origin
        // for 999 ticks in a row, consider it the closest in the long term
        break;
    }
}

echo "Day 20.1: $closestParticleId ($tickSmallestDist, $ticks, $i)\n";

$count = 0;
foreach ($particles as $particle) {
    if (!$particle["destroyed"]) {
        $count++;
    }
}

echo "Day 20.2: $count\n";
