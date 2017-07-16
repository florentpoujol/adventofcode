<?php

$resource = fopen("3_input.php", "r");
$line = "";
$sizes = [];
$count = 0;

while (($line = fgets($resource)) !== false) {
    $line = trim($line);
    $matches = [];
    
    preg_match("/^([0-9]+)( +)([0-9]+)( +)([0-9]+)$/", $line, $matches);
    $sizes = [(int)$matches[1], (int)$matches[3], (int)$matches[5]];
    
    sort($sizes);

    if ($sizes[0] + $sizes[1] > $sizes[2])
        $count++;
}

var_dump($count);
