<?php

$resource = fopen("9_input.txt", "r");

$data = [];

while (($line = fgets($resource)) !== false) {
    $line = trim($line);

    // first build data from the input
    // ie = $data["London"] = ["Dublin" => 464, "Belfast" => 518]
    $matches = [];
    preg_match("/([a-z]+) to ([a-z]+) = ([0-9]+)/i", $line, $matches);
    
    if (! isset($data[$matches[1]])) {
        $data[$matches[1]] = [];
    }
    $data[$matches[1]][$matches[2]] = (int)$matches[3];

    // repeat the information but on the other direction
    if (! isset($data[$matches[2]])) {
        $data[$matches[2]] = [];
    }
    $data[$matches[2]][$matches[1]] = (int)$matches[3];
}

// var_dump($data);

// algo : take a city and go to the closest, then to the next closest whitout going to the same city twice
// do that for eac city

$cities = array_keys($data);
$cityCount = count($cities);
$shortestDistPerCity = []; // shortest distance per STARTING city

foreach ($cities as $city) {

    $shortestDistPerCity[$city] = 0;
    $visitedCities = [$city];

    $distPerCity = $data[$city];

    for ($i=0; $i < $cityCount; $i++) { 

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
            // var_dump($city, $closestCity);
            $shortestDistPerCity[$city] += $distPerCity[$closestCity];
            $visitedCities[] = $closestCity;
            $distPerCity = $data[$closestCity];
        }
    }
}


// var_dump($shortestDistPerCity);
$shortestDist = array_values($shortestDistPerCity);
sort($shortestDist);
$shortestDist = $shortestDist[0];
echo "day 9.1: $shortestDist <br>";
