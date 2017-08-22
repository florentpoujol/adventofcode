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

var_dump($data);

// algo : take a city and go to the fartest, then to the next fartest whitout going to the same city twice
// do that for eac city

$cities = array_keys($data);
$longestDistPerCity = []; // longest distance per STARTING city

foreach ($cities as $city) {

    $longestDistPerCity[$city] = 0;
    $visitedCities = [$city];

    $distPerCity = $data[$city];

    for ($i=0; $i < count($cities); $i++) { 

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


var_dump($longestDistPerCity);
$longestDist = array_values($longestDistPerCity);
rsort($longestDist);
$longestDist = $longestDist[0];
echo "day 9.2: $longestDist <br>";
