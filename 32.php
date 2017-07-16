<?php

$resource = fopen("3_input.php", "r");
$line = "";
$sizes = [[], [], []];
$count = 0;

// read lines by batch of 3
$loop = 0;
while (($line = fgets($resource)) !== false) {
    $loop++;

    $matches = [];    
    preg_match("/([0-9]+)( +)([0-9]+)( +)([0-9]+)/", $line, $matches);
    array_push($sizes[0], (int)$matches[1]);
    array_push($sizes[1], (int)$matches[3]);
    array_push($sizes[2], (int)$matches[5]);

    if ($loop % 3 === 0) {
    
        for ($i=0; $i < 3; $i++) { 
            sort($sizes[$i]);
           
            if ($sizes[$i][0] + $sizes[$i][1] > $sizes[$i][2]) {
                $count++;
            }
        }

        $sizes = [[], [], []];
    }
}

var_dump($count);
