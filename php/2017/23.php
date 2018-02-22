<?php
// http://adventofcode.com/2017/day/23
// PART 1

$registers = ["a" => 0, "b" => 0, "c" => 0, "d" => 0, "e" => 0, "f" => 0, "g" => 0, "h" => 0];
$instructions = [];
$resource = fopen("23_input.txt", "r");

while (($line = fgets($resource)) !== false) {
    $parts = explode(" ", trim($line));
    if (is_numeric($parts[1])) {
        $parts[1] = (int)$parts[1];
    }
    if (isset($parts[2]) && is_numeric($parts[2])) {
        $parts[2] = (int)$parts[2];
    }

    $instructions[] = [
        "name" => $parts[0],
        "op1" => $parts[1],
        "op2" => $parts[2] ?? null,
    ];
}

function getRegisterValue($nameOrValue)
{
    if (is_string($nameOrValue)) {
        global $registers;
        $nameOrValue = $registers[$nameOrValue];
    }
    return $nameOrValue;
}

$mulCount = 0;
$instrCount = count($instructions);
for ($i = 0; $i >= 0 && $i < $instrCount; $i++) {
    $name = $instructions[$i]["name"];
    $op1 = $instructions[$i]["op1"];
    $op2 = $instructions[$i]["op2"];

    switch ($name) {
        case "set":
            $registers[$op1] = getRegisterValue($op2);
            break;
        case "sub":
            $registers[$op1] -= getRegisterValue($op2);
            break;
        case "mul":
            $registers[$op1] *= getRegisterValue($op2);
            $mulCount++;
            break;

        case "jnz":
            if (getRegisterValue($op1) !== 0) {
                $i += getRegisterValue($op2) - 1; // -1 because the for loop does + 1
            }
            break;

        default:
            var_dump($instructions[$i]);
            exit("unknow instruction: $i");
    }
}

echo "Day 23.1: $mulCount\n";

// part 2
// analysis of the algo would reveal that all it does
// is check which numbers are prime from $b to $c, with $b incremented by 17

// i decomposed the algo correctly
// but didn't noticed it was a check for a prime number
// (i had to look that up on reddit)

$h = 0;
for ($b = 107900; $b <= 124900; $b += 17) {
    for ($d = 2; $d < $b; $d++) {
        if ($b % $d === 0) {
            $h++;
            break;
        }
    }
}

echo "Day 23.2: $h\n";
