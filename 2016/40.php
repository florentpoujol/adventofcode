<?php

$resource = fopen("40_input.txt", "r");
$line = "";
$sizes = [];

$sectorIdSum = 0;

$alphabet = ["a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z"];
$npoSectorId = 0;

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
        $sectorId = (int)$matches[2];
        $sectorIdSum += $sectorId;

        if ($npoSectorId === 0) {
            $actualRoomName = "";
            $letters = str_split($matches[1]);

            foreach ($letters as $letter) {
                if ($letter !== "-") {
                    $letterId = array_search($letter, $alphabet);
                    $actualLetterId = (($sectorId + $letterId) % 26);
                    // var_dump($actualLetterId);
                    // var_dump($alphabet[$actualLetterId]);
                    $actualRoomName .= $alphabet[$actualLetterId];
                } else {
                    $actualRoomName .= " ";
                }
            }

            if (strpos($actualRoomName, "north") !== false && strpos($actualRoomName, "pole") !== false && strpos($actualRoomName, "object") !== false) {
                // actual name = "northpole objects storage"
                $npoSectorId = $sectorId;
            }
        }
    }
}

echo "day 1: $sectorIdSum <br>";
echo "day 2: $npoSectorId <br>";
