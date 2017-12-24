<?php
// http://adventofcode.com/2015/day/13

$resource = fopen("14_input.txt", "r");

$paramsPerName = [];
$matches = [];

while (($line = fgets($resource)) !== false) {
    preg_match("/^([a-z]+) can fly ([0-9]+) Km\/s for ([0-9]+) .+ ([0-9]+)/i", $line, $matches);

    $paramsPerName[$matches[1]] = [
        "speed" => (int)$matches[2],
        "flyTime" => (int)$matches[3],
        "restTime" => (int)$matches[4],

        // for part 2
        "totalTime" => (int)$matches[3] + (int)$matches[4],
        "points" => 0,
        "distance" => 0,
        "remainingFlyTime" => 0,
    ];
}

// $targetFlyTime = 1000; // test
$targetFlyTime = 2503;
$distancesPerName = [];

foreach ($paramsPerName as $name => $params) {
    $totalTime = $params["flyTime"] + $params["restTime"];
    $sections = floor($targetFlyTime / $totalTime);
    $flyDistancePerSection = $params["speed"] * $params["flyTime"];
    $distance = $flyDistancePerSection * $sections;

    $remainingTime = $targetFlyTime - ($totalTime * $sections);

    if ($remainingTime <= $params["flyTime"]) {
        $distance += $params["speed"] * $remainingTime;
    } else {
        // is in its rest phase
        $distance += $flyDistancePerSection;
    }
    $distancesPerName[$name] = $distance;
}

$dist = max(...array_values($distancesPerName));
echo "Day 14.1: $dist\n";

// part 2

for ($time = 1; $time <= $targetFlyTime; $time++) {
    $maxDistance = 0;
    $winners = [];

    foreach ($paramsPerName as $name => $params) {
        if ($time === 1) {
            $params["remainingFlyTime"] = $params["flyTime"];
        }

        if ($params["remainingFlyTime"] > 0) {
            $params["distance"] += $params["speed"];
            $params["remainingFlyTime"]--;
        }

        if ($params["distance"] > $maxDistance) {
            $maxDistance = $params["distance"];
            $winners = [$name];
        } elseif ($params["distance"] === $maxDistance) {
            $winners[] = $name;
        }

        if ($time % $params["totalTime"] === 0) {
            $params["remainingFlyTime"] = $params["flyTime"];
        }

        $paramsPerName[$name] = $params;
    }

    foreach ($winners as $winner) {
        $paramsPerName[$winner]["points"]++;
    }
}

$maxPoint = 0;
foreach ($paramsPerName as $params) {
    $maxPoint = max($maxPoint, $params["points"]);
}

echo "Day 14.2: $maxPoint\n";
