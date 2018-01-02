<?php
// http://adventofcode.com/2016/day/12

$lines = explode("\n", file_get_contents("12_input.txt"));

$instructions = [];

foreach ($lines as $line) {
    $parts = explode(" ", $line);

    $op1 = $parts[1];
    if (is_numeric($op1)) {
        $op1 = (int)$op1;
    }

    $op2 = null;
    if (isset($parts[2])) {
        $op2 = $parts[2];
        if (is_numeric($op2)) {
            $op2 = (int)$op2;
        }
    }

    $instructions[] = [
        "name" => $parts[0],
        "op1" => $op1,
        "op2" => $op2,
    ];
}

$registers = ["a" => 0, "b" => 0, "c" => 0, "d" => 0];

function run()
{
    global $instructions, $registers;
    $instrCount = count($instructions);

    for ($i = 0; $i >= 0 && $i < $instrCount; $i++) {
        $instr = $instructions[$i];
        $op1 = $instr["op1"];
        $op2 = $instr["op2"];

        switch ($instr["name"]) {
            case "cpy":
                if (!is_numeric($op1)) {
                    $op1 = $registers[$op1];
                }
                $registers[$op2] = $op1;
                break;
            case "inc":
                $registers[$op1]++;
                break;
            case "dec":
                $registers[$op1]--;
                break;
            case "jnz":
                if (!is_numeric($op1)) {
                    $op1 = $registers[$op1];
                }
                if ($op1 !== 0) {
                    $i += $op2 - 1;
                }
                break;
            default:
                var_dump($instr);
                exit("wrong instruction");
                break;
        }
    }
}

run();
echo "Day 12.1: $registers[a]\n";

$registers = ["a" => 0, "b" => 0, "c" => 1, "d" => 0];
run();
echo "Day 12.2: $registers[a]\n";
