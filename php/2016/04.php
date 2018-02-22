<?php
// http://adventofcode.com/2016/day/3

$resource = fopen("04_input.txt", "r");

$sizes = [];
$alphabet = ["a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z"];
$sectorIdSum = 0;
$npoSectorId = 0;
$matches = [];

while (($line = fgets($resource)) !== false) {
    preg_match("/^([a-z-]+)([0-9]+)\[([a-z]+)\]/", $line, $matches);
    
    $lettersByFrequency = [];
    $roomName = str_replace("-", "", $matches[1]);
    $letters = str_split($roomName);

    foreach ($letters as $letter) {
        $freq = substr_count($roomName, $letter);

        if (!isset($lettersByFrequency[$freq])) {
            $lettersByFrequency[$freq] = [];
        }

        if (!in_array($letter, $lettersByFrequency[$freq])) {
            $lettersByFrequency[$freq][] = $letter;
        }
    }

    krsort($lettersByFrequency);
    $expectedChecksum = "";
    foreach ($lettersByFrequency as $freq => $letters) {
        sort($letters);
        $expectedChecksum .= implode("", $letters);
    }
    $expectedChecksum = substr($expectedChecksum, 0, 5);

    if ($expectedChecksum === $matches[3]) {
        $sectorId = (int)$matches[2];
        $sectorIdSum += $sectorId;

        if ($npoSectorId === 0) {
            $actualRoomName = "";
            $roomNameLetters = str_split($matches[1]);

            foreach ($roomNameLetters as $letter) {
                if ($letter === "-") {
                    $actualRoomName .= " ";
                } else {
                    $letterId = array_search($letter, $alphabet);
                    $actualLetterId = (($sectorId + $letterId) % 26);
                    $actualRoomName .= $alphabet[$actualLetterId];
                }
            }

            if (
                strpos($actualRoomName, "north") !== false &&
                strpos($actualRoomName, "pole") !== false &&
                strpos($actualRoomName, "object") !== false
            ) {
                // actual name = "northpole objects storage"
                $npoSectorId = $sectorId;
            }
        }
    }
}

echo "Day 4.1: $sectorIdSum <br>";
echo "Day 4.2: $npoSectorId <br>";
