<?php

$resource = fopen("14_input.txt", "r");

$paramsPerName = [];

while (($line = fgets($resource)) !== false) {
    $line = trim($line);

    $matches = [];
    preg_match("/^([a-z]+) can fly ([0-9]+) Km\/s for ([0-9]+) .+ ([0-9]+)/i", $line, $matches);

    $paramsPerName[$matches[1]] = [
        "speed" => (int)$matches[2],
        "flyTime" => (int)$matches[3],
        "restTime" => (int)$matches[4]
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

var_dump($distancesPerName); // 1120  too low