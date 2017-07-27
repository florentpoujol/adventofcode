<?php
$res = fopen("92_input_test.txt", "r");
$res = fopen("90_input.txt", "r");

$count= 0;

function processMarkers($line)
{
    var_dump($line);
    $decompressedLength = 0; // strings become to big to be stored, so only store their length
    $startCharId = 0;
    $lineLength = strlen($line);
    $matches = [];
    $p = "/^([A-Z]+)?(\(([0-9]+)x([0-9]+)\))?/";

    while (
        $startCharId < $lineLength &&
        preg_match($p, substr($line, $startCharId, 15), $matches) === 1
    ) { 
        var_dump($matches);

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

        var_dump($startCharId);
    }

    return $decompressedLength;
}

while (($line = fgets($res)) !== false) {
    $line = trim($line);
    $decompressedLength = 0;
    
    var_dump("------------------------");

    $decompressedLength = processMarkers($line);

    $count += $decompressedLength;
    var_dump( $decompressedLength, "------------------------");
}


echo "day 9.2: $count ";
