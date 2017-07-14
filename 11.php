<?php

$inputs = ["R2", "L3"];
$inputs = ["R2", "R2", "R2"];
$inputs = ["R5", "L5", "R5", "R3"];
$inputs = ["L2", "L5", "L5", "R5", "L2", "L4", "R1", "R1", "L4", "R2", "R1", "L1", "L4", "R1", "L4", "L4", "R5", "R3", "R1", "L1", "R1", "L5", "L1", "R5", "L4", "R2", "L5", "L3", "L3", "R3", "L3", "R4", "R4", "L2", "L5", "R1", "R2", "L2", "L1", "R3", "R4", "L193", "R3", "L5", "R45", "L1", "R4", "R79", "L5", "L5", "R5", "R1", "L4", "R3", "R3", "L4", "R185", "L5", "L3", "L1", "R5", "L2", "R1", "R3", "R2", "L3", "L4", "L2", "R2", "L3", "L2", "L2", "L3", "L5", "R3", "R4", "L5", "R1", "R2", "L2", "R4", "R3", "L4", "L3", "L1", "R3", "R2", "R1", "R1", "L3", "R4", "L5", "R2", "R1", "R3", "L3", "L2", "L2", "R2", "R1", "R2", "R3", "L3", "L3", "R4", "L4", "R4", "R4", "R4", "L3", "L1", "L2", "R5", "R2", "R2", "R2", "L4", "L3", "L4", "R4", "L5", "L4", "R2", "L4", "L4", "R4", "R1", "R5", "L2", "L4", "L5", "L3", "L2", "L4", "L4", "R3", "L3", "L4", "R1", "L2", "R3", "L2", "R1", "R2", "R5", "L4", "L2", "L1", "L3", "R2", "R3", "L2", "L1", "L5", "L2", "L1", "R4"];

$dir = 0; // 0=north, 1=east, 2=south, 3=west
$coords = [0, 0]; // 0 is west-east, 1 is south-north

foreach ($inputs as $input) {
    $rotation = $input[0];
    
    if ($rotation === "L")
        $dir--;
    else
        $dir++;

    if ($dir === -1)
        $dir = 3;
    elseif ($dir === 4)
        $dir = 0;
    

    $array = str_split($input);
    array_shift($array);
    $count = (int)implode($array);

    switch ($dir) {
        case 0:
            $coords[1] += $count; // north
            break;
        case 1:
            $coords[0] += $count; // east
            break;
        case 2:
            $coords[1] -= $count; // south
            break;
        case 3:
            $coords[0] -= $count; // west
            break;
    }
}

var_dump($coords);
$distance = abs($coords[0]) + abs($coords[1]);
echo "distance: $distance";