<?php
$res = fopen("90_input.txt", "r");

$count = 0;

while (($line = fgets($res)) !== false) {
    $line = trim($line);
    $decompressedLine = "";
    $startCharId = 0;

    var_dump($line);

    $lineLength = strlen($line);
    $matches = [];
    $p = "/^([A-Z]+)?(\(([0-9]+)x([0-9]+)\))?/";

    while (
        $startCharId < $lineLength &&
        preg_match($p, substr($line, $startCharId, 9), $matches) === 1
    ) { // 9 = allow each number to be 3 char long
        
        var_dump($matches);

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

        var_dump($startCharId);
    }

    var_dump($decompressedLine, "------------------------");
    $count += strlen($decompressedLine);
}


echo "day 9.1: $count <br>";
