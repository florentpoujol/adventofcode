<?php
// http://adventofcode.com/2015/day/23

$resource = fopen("23_input.txt", "r");
$matches = [];
$instructions = [];
while (($line = fgets($resource)) !== false) {
    preg_match("/^([a-z]{3}) (.+)$/", trim($line), $matches);
    $name = $matches[1];
    $value = $matches[2];

    if ($name === "jmp") {
        $value = (int)$value;
    } elseif ($name === "jie" || $name === "jio") {
        $value = explode(", ", $value);
        $value[1] = (int)$value[1];
    }

    $instructions[] = [
        "name" => $name,
        "value" => $value,
    ];
}

function run(int $regA)
{
    global $instructions;
    $registers = ["a" => $regA, "b" => 0];

    $instrCount = count($instructions);
    for ($i = 0; $i >= 0 && $i < $instrCount; $i++) {
        $instr = $instructions[$i];
        $value = $instr["value"];

        switch ($instr["name"]) {
            case "hlf":
                $registers[$value] /= 2;
                break;
            case "tpl":
                $registers[$value] *= 3;
                break;
            case "inc":
                $registers[$value]++;
                break;
            case "jmp":
                $i += $value - 1;
                break;
            case "jie":
                if ($registers[$value[0]] % 2 === 0) {
                    $i += $value[1] - 1;
                }
                break;
            case "jio":
                if ($registers[$value[0]] === 1) {
                    $i += $value[1] - 1;
                }
                break;
            default:
                var_dump($instr);
                exit("unknow instruction");
                break;
        }
    }

    return $registers;
}

$registers = run(0);
echo "Day 23.1: $registers[b]\n";

$registers = run(1);
echo "Day 23.2: $registers[b]\n";
