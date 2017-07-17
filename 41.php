<?php

$resource = fopen("40_input.txt", "r");
$line = "";
$sizes = [];

$sectorIdSum = 0;

while (($line = fgets($resource)) !== false) {

    $matches = [];
    $match = preg_match("/^([a-z-]+)([0-9]+)\[([a-z]+)\]$/", $line, $matches);
    
    $lettersByFrequency = [];
    $roomName = str_replace("-", "", $matches[1]);
    $letters = str_split($roomName);

    foreach ($letters as $letter) {
        $freq = substr_count($roomName, $letter);

        if (! array_key_exists($freq, $lettersByFrequency)) {
            $lettersByFrequency[$freq] = [];
        }

        if (! in_array($letter, $lettersByFrequency[$freq])) {
            $lettersByFrequency[$freq][] = $letter;
        }
    }

    krsort($lettersByFrequency);
    $expectedChecksum = "";

    foreach ($lettersByFrequency as $freq => $letters) {
        sort($letters);
        $expectedChecksum .= implode("", $letters);
    }
    
    $expectedChecksum = implode("", array_slice(str_split($expectedChecksum), 0, 5));

    if ($expectedChecksum === $matches[3]) {
        $sectorIdSum += (int)$matches[2];
    }
}

echo "day 1: $sectorIdSum <br>";