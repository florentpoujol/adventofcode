<?php
// http://adventofcode.com/2015/day/9

$resource = fopen("9_input.txt", "r");

$data = [];
// ie = $data["London"] = ["Dublin" => 464, "Belfast" => 518]
$matches = [];

while (($line = fgets($resource)) !== false) {
    preg_match("/([a-z]+) to ([a-z]+) = ([0-9]+)/i", $line, $matches);

    $city1 = $matches[1];
    $city2 = $matches[2];
    $dist = (int)$matches[3];

    if (!isset($data[$city1])) {
        $data[$city1] = [];
    }
    $data[$city1][$city2] = $dist;

    // repeat the information but on the other direction
    if (!isset($data[$city2])) {
        $data[$city2] = [];
    }
    $data[$city2][$city1] = $dist;
}

$cities = array_keys($data);
$cityCount = count($cities);

$shortestDistPerCity = []; // shortest distance per STARTING city

foreach ($cities as $city) {
    $shortestDistPerCity[$city] = 0;
    $visitedCities = [$city];
    $distPerCity = $data[$city];

    for ($i = 0; $i < $cityCount; $i++) {
        while (1) {
            $closestCity = "";
            $shortestDist = 99999;

            foreach ($distPerCity as $_city => $dist) {
                if ($dist < $shortestDist) {
                    $closestCity = $_city;
                    $shortestDist = $dist;
                }
            }

            if (
                $closestCity === "" || // when all cities will have already been visited
                ! in_array($closestCity, $visitedCities) // city not yet visited
            ) {
                break;
            } else {
                // closest city already visited
                // remove it from the set to get the next closest city
                unset($distPerCity[$closestCity]);
            }
        }

        if ($closestCity !== "") {
            $shortestDistPerCity[$city] += $distPerCity[$closestCity];
            $visitedCities[] = $closestCity;
            $distPerCity = $data[$closestCity];
        }
    }
}

$shortestDist = array_values($shortestDistPerCity);
sort($shortestDist);
echo "Day 9.1: $shortestDist[0] <br>";

// part 2

$longestDistPerCity = []; // longest distance per STARTING city

foreach ($cities as $city) {
    $longestDistPerCity[$city] = 0;
    $visitedCities = [$city];
    $distPerCity = $data[$city];

    for ($i = 0; $i < $cityCount; $i++) {
        while (1) {
            $fartestCity = "";
            $longestDist = 0;
            foreach ($distPerCity as $_city => $dist) {
                if ($dist > $longestDist) {
                    $fartestCity = $_city;
                    $longestDist = $dist;
                }
            }

            if (
                $fartestCity === "" || // when all cities will have already been visited
                ! in_array($fartestCity, $visitedCities) // city not yet visited
            ) {
                break;
            } else {
                // fartest city already visited
                // remove it from the set to get the next fartest city
                unset($distPerCity[$fartestCity]);
            }
        }

        if ($fartestCity !== "") {
            $longestDistPerCity[$city] += $distPerCity[$fartestCity];
            $visitedCities[] = $fartestCity;
            $distPerCity = $data[$fartestCity];
        }
    }
}

$longestDist = array_values($longestDistPerCity);
rsort($longestDist);
echo "Day 9.2: $longestDist[0] <br>";
