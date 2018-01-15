<?php
// http://adventofcode.com/2016/day/23

$lines = explode("\n", file_get_contents("23_input.txt"));
/*$lines = [
    "cpy 2 a",
    "tgl a",
    "tgl a",
    "tgl a",
    "cpy 1 a",
    "dec a",
    "dec a",
];*/
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

//var_dump($instructions);

function printInstr()
{
    global $instructions, $registers;
    var_dump($registers);
    foreach ($instructions as $instr) {
        echo "$instr[name] $instr[op1] $instr[op2]\n";
    }
}

function run($part2 = false)
{
    global $instructions, $registers;
    $instrCount = count($instructions);

    for ($i = 0; $i >= 0 && $i < $instrCount; $i++) {
        $instr = $instructions[$i];
        $op1 = $instr["op1"];
        $op2 = $instr["op2"];

        switch ($instr["name"]) {
            case "cpy":
                if (is_numeric($op2)) {
                    break;
                }

                if (!is_numeric($op1)) {
                    $op1 = $registers[$op1];
                }
                $registers[$op2] = $op1;
                break;

            case "inc":
                if (is_numeric($op1)) {
                    break;
                }

                if ($multiply) {
                    $val = $registers[$op1];
                    $registers[$op1] *= $val;
                } else {
                    $registers[$op1]++;
                }
                break;

            case "dec":
                if (is_numeric($op1)) {
                    break;
                }

                $registers[$op1]--;
                break;

            case "jnz":
                if ($part2) {


                    if ($i === 7) {
                        $c = $registers["c"];
                        if ($c > 0) {
                            $registers["a"] += $c;
                            $registers["c"] = 0;
                        } elseif ($c < 0) {
                            exit("error infinite loop 1");
                        }
                        break;
                    } elseif ($i === 15) {
                        $d = $registers["d"];
                        if ($d > 0) {
                            $registers["c"] += $d;
                            $registers["d"] = 0;
                        } elseif ($d < 0) {
                            exit("error infinite loop 2");
                        }
                        break;
                    } elseif ($i === 23) {
                        $d = $registers["d"];
                        if ($d < 0) {
                            $registers["a"] += abs($d);
                            $registers["d"] = 0;
                        } elseif ($d > 0) {
                            var_dump("error infinite loop 3", $registers, $instr);
                            exit;
                        }
                        break;
                    }
                }

                if (!is_numeric($op1)) {
                    $op1 = $registers[$op1];
                }
                if (!is_numeric($op2)) {
                    $op2 = $registers[$op2];
                }
                if ($op1 !== 0) {
                    $i += $op2 - 1;
                }
                break;

            case "tgl":
                if (!is_numeric($op1)) {
                    $op1 = $registers[$op1];
                }
                $targetI = $i + $op1;
                if ($targetI < 0 || $targetI >= $instrCount) {
                    break;
                }

                $name = $instructions[$targetI]["name"];
                if ($name === "inc") {
                    $name = "dec";
                } elseif ($name === "dec" || $name === "tgl") {
                    $name = "inc";
                } elseif ($name === "jnz") {
                    $name = "cpy";
                } else {
                    // never happens
                    $name = "jnz";
                }
                $instructions[$targetI]["name"] = $name;
                break;

            default:
                var_dump($instr);
                exit("wrong instruction $i");
                break;
        }
    }
}

$registers = ["a" => 7, "b" => 0, "c" => 0, "d" => 0];
$savedInstructions = $instructions;
run();
echo "Day 23.1: $registers[a]\n";

$registers = ["a" => 12, "b" => 0, "c" => 0, "d" => 0];
$instructions = $savedInstructions;
run(true);

echo "Day 23.2: $registers[a]\n";
