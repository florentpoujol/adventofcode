<?php
// http://adventofcode.com/2016/day/23

$lines = explode("\n", file_get_contents("23_input.txt"));
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
    $j = 0;
    for ($i = 0; $i >= 0 && $i < $instrCount; $i++) {
        $j++;
        /*if ($j > 9999995) {
            var_dump($instructions);
            break;
        }*/
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

                $registers[$op1]++;
                break;

            case "dec":
                if (is_numeric($op1)) {
                    break;
                }

                $registers[$op1]--;
                break;

            case "jnz":
                if ($part2) {
                    if ($i === 9) {
                        // special -5 loop
                        // we assume here that the instructions don't change in part 2
                        /*cpy b c
                          inc a
                          dec c
                          jnz c -2
                          dec d
                          jnz d -5*/
                        // we have to increase a by b * c
                        $registers["a"] += $registers["b"] * $registers["d"]; // at this point, $registers["c"] === 0, so that's why we take register B
                        $registers["c"] = 0;
                        $registers["d"] = 0;
                        break;
                    }

                    // optimize some -2 loops
                    // this part is actually not necessary, it is fast enough with just the opti above
                    // it still does reduce  the number of total loop dones ~700 from ~ 19000
                    if ($i === 7 || $i === 15 || $i === 23) {
                        // don't hardcode register name and direction of increase
                        // it may have been changed by the tgl instruction

                        $zeroRegister = $op1; // the register which value is checked in the loop and that gets set to 0

                        $instr2 = $instructions[$i - 2];
                        $instr1 = $instructions[$i - 1];
                        if ($instr2["op1"] !== $zeroRegister) {
                            $instr = $instr2; // this is the instruction that change the register we are interested in
                            $zeroInstrName = $instr1["name"];
                        } else {
                            $instr = $instr1;
                            $zeroInstrName = $instr2["name"];
                        }
                        $otherRegister = $instr["op1"];

                        if ($instr["name"] === "tgl" || $zeroInstrName === "tgl") {
                            exit("tgl in loop");
                        }

                        // detect infinite loop
                        $count = $registers[$zeroRegister];
                        if (($count < 0 && $zeroInstrName === "dec") ||
                            ($count > 0 && $zeroInstrName === "inc")) {
                            exit("infinite loop");
                        }

                        $registers[$zeroRegister] = 0;

                        if ($instr["name"] === "inc") {
                            $registers[$otherRegister] += abs($count);
                        } else {
                            $registers[$otherRegister] -= abs($count);
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
    var_dump($j);
}

$registers = ["a" => 7, "b" => 0, "c" => 0, "d" => 0];

$savedInstructions = $instructions;
run(false);
echo "Day 23.1: $registers[a]\n";

$registers = ["a" => 12, "b" => 0, "c" => 0, "d" => 0];
$instructions = $savedInstructions;
run(true);

echo "Day 23.2: $registers[a]\n"; // 5966 too low
