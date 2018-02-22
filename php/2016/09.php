<?php
// http://adventofcode.com/2016/day/9

$line = trim(file_get_contents("09_input.txt"));
$lineLength = strlen($line);

$decompressedLine = "";
$startCharId = 0;

$matches = [];
$p = "/^([A-Z]+)?(\(([0-9]+)x([0-9]+)\))?/";

while (
    $startCharId < $lineLength &&
    preg_match($p, substr($line, $startCharId, 9), $matches) === 1
) { // 9 = allow each number to be 3 char long

    // var_dump($matches);

    $startCharId += strlen($matches[0]);

    if (isset($matches[1])) {
        $beforeChunk = $matches[1];
        $decompressedLine .= $beforeChunk;
    }

    if (isset($matches[2])) {
        $chunkSize = (int)$matches[3];
        $repeatCount = (int)$matches[4];

        $chunk = substr($line, $startCharId, $chunkSize);

        $decompressedLine .= str_repeat($chunk, $repeatCount);

        $startCharId += $chunkSize;
    }
}

// var_dump($decompressedLine, "------------------------");
$count = strlen($decompressedLine);
echo "day 9.1: $count <br>";

// part 2

// same as the algo for part 1 bu we don't store the actual decompressed string
// only its length
function processMarkers($line)
{
    // var_dump($line);
    $decompressedLength = 0; // strings become to big to be stored, so only store their length
    $startCharId = 0;
    $lineLength = strlen($line);
    $matches = [];
    $p = "/^([A-Z]+)?(\(([0-9]+)x([0-9]+)\))?/";

    while (
        $startCharId < $lineLength &&
        preg_match($p, substr($line, $startCharId, 15), $matches) === 1
    ) {
        // var_dump($matches);

        $startCharId += strlen($matches[0]);

        if (isset($matches[1])) {
            $beforeChunk = $matches[1];
            $decompressedLength += strlen($beforeChunk);
        }

        if (isset($matches[2])) {
            $chunkSize = (int)$matches[3];
            $repeatCount = (int)$matches[4];

            $chunk = substr($line, $startCharId, $chunkSize);

            $decompressedLength += (processMarkers($chunk) * $repeatCount);

            $startCharId += $chunkSize;
        }

    }

    return $decompressedLength;
}

$decompressedLength = processMarkers($line);

echo "day 9.2: $decompressedLength\n";
