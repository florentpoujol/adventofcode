<?php

$resource = fopen("02_input.txt", "r");

$sum  = 0;

while(($line = fgets($resource)) !== false) {
    $line = trim($line);
    if ($line === "") {
        break;
    }

    $numbers = array_filter(explode(" ", $line), function($value) { return is_numeric($value); });
        
    $sum += max($numbers) - min($numbers);
}

echo "Day 2.1: $sum \n";

// $resource = fopen("02.2_input_test.txt", "r");

$sum  = 0;
rewind($resource);

while(($line = fgets($resource)) !== false) {
    $line = trim($line);
    if ($line === "") {
        break;
    }

    $numbers = array_filter(explode(" ", $line), function($value) { return is_numeric($value); });
    $numbers = array_map(function($value) { return (int)$value; }, $numbers);
    
    $result = 0;
    
    foreach ($numbers as $id => $dividend) {
        foreach ($numbers as $id2 => $divisor) {
            if ($id2 === $id || $divisor === 0) {
                continue;
            }

            $_result = $dividend / $divisor;
            if (is_int($_result)) {
                $result = $_result;
                break(2);
            }
        }
    }

    $sum += $result;
}

echo "Day 2.2: $sum \n";
