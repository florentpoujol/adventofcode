<?php
// http://adventofcode.com/2015/day/14

$resource = fopen("14_input.txt", "r");

$paramsPerName = [];

while (($line = fgets($resource)) !== false) {
    $line = trim($line);

    $matches = [];
    preg_match("/^([a-z]+) can fly ([0-9]+) Km\/s for ([0-9]+) .+ ([0-9]+)/i", $line, $matches);

    $paramsPerName[$matches[1]] = [
        "speed" => (int)$matches[2],
        "flyTime" => (int)$matches[3],
        "restTime" => (int)$matches[4],
        "totalTime" => (int)$matches[3] + (int)$matches[4],
        "points" => 0,
        "distance" => 0,
        "remainingFlyTime" => 0,
    ];
}

//var_dump($paramsPerName);

$targetFlyTime = 1000; // test
$targetFlyTime = 2503;

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

var_dump($paramsPerName); // 1317  too high
