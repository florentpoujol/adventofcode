<?php
// http://adventofcode.com/2017/day/18

$registers = [];
$lastFrequency = 0;
$instructions = [];

$test = "";
// $test = "_test"; // test
$resource = fopen("18_input$test.txt", "r");

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

    // register registers
    $registerInstr = ["set", "add", "mul", "mod"];
    if (in_array($parts[0], $registerInstr)) {
        $regName = $parts[1];
        if (is_string($regName) && !isset($registers[$regName])) {
            $registers[$regName] = 0;
        }
        $regName = $parts[2];
        if (is_string($regName) && !isset($registers[$regName])) {
            $registers[$regName] = 0;
        }
    }
}

function getRegisterValue($nameOrValue, $pId = null)
{
    if (is_string($nameOrValue)) {
        global $registers;
        $regs = $registers;
        if ($pId !== null) { // $used for part 2
            $regs = $registers[$pId];
        }
        $nameOrValue = $regs[$nameOrValue];
    }
    return $nameOrValue;
}

// var_dump($instructions);
$recoverFreq = -987;
$instrCount = count($instructions);
for ($i = 0; $i >= 0 && $i < $instrCount; $i++) {
    // list("name" => $name, "op1" => $op1, "op2" => $op2) = $instructions[$i]; // using => require PHP7.1, but is not specified in the list() manual...
    $name = $instructions[$i]["name"];
    $op1 = $instructions[$i]["op1"];
    $op2 = $instructions[$i]["op2"];
    switch ($name) {
        case "snd":
            $lastFrequency = getRegisterValue($op1);
            break;
        case "rcv":
            if (getRegisterValue($op1) !== 0) {
                $recoverFreq = $lastFrequency;
                break(2);
            }
            break;

        case "set":
            $registers[$op1] = getRegisterValue($op2);
            break;
        case "add":
            $registers[$op1] += getRegisterValue($op2);
            break;
        case "mul":
            $registers[$op1] *= getRegisterValue($op2);
            break;
        case "mod":
            $registers[$op1] %= getRegisterValue($op2);
            break;

        case "jgz":
            if (getRegisterValue($op1) > 0) {
                $i += getRegisterValue($op2) - 1; // -1 because the for loop does +1 (I could also
            }
            break;

        default:
            var_dump($instructions[$i]);
            exit("unknow instruction: $i");
    }
}

echo "Day 18.1: $recoverFreq\n";

// part 2

// start test instructions
$test = false;
// $test = true; // test
if ($test) {
    $instructions = [];
    $resource = fopen("18_input_test2.txt", "r");
    while (($line = fgets($resource)) !== false) {
        $parts = explode(" ", trim($line));
        if (is_numeric($parts[1])) {
            $parts[1] = (int)$parts[1];
        }
        $instructions[] = [
            "name" => $parts[0],
            "op1" => $parts[1],
            "op2" => null,
        ];
    }
}
// end test

foreach ($registers as $name => &$value) {
    $value = 0;
}
$registers = [$registers, $registers];
$registers[0]["p"] = 0;
$registers[1]["p"] = 1;

$queues = [[], []];

$sendCount = 0;


// the function returns the instruction id at which it stopped
function run(int $pId, int $startInstrId)
{
    global $instructions, $registers, $queues, $sendCount;

    $otherpId = (int)!$pId; // pid of the other program
    $instrCount = count($instructions);

    if ($startInstrId === -1) {
        $startInstrId = 0; // only happens the first time the function is called
    }

    for ($i = $startInstrId; $i >= 0 && $i < $instrCount; $i++) {
        $name = $instructions[$i]["name"];
        $op1 = $instructions[$i]["op1"];
        $op2 = $instructions[$i]["op2"];
        switch ($name) {

            case "snd":
                $value = getRegisterValue($op1, $pId);
                $queues[$otherpId][] = $value;
                if ($pId === 1) {
                    $sendCount++;
                }
                // do not yield or return here
                // let this program go up to its first wait for receive a value
                break;

            case "rcv":
                if (empty($queues[$pId])) {
                    return $i;
                    // return this instr id
                    // next time the function will be called with this id and a non-empty queue
                }

                $value = array_shift($queues[$pId]);
                $registers[$pId][$op1] = $value;
                break;

            case "set":
                $registers[$pId][$op1] = getRegisterValue($op2, $pId);
                break;
            case "add":
                $registers[$pId][$op1] += getRegisterValue($op2, $pId);
                break;
            case "mul":
                $registers[$pId][$op1] *= getRegisterValue($op2, $pId);
                break;
            case "mod":
                $registers[$pId][$op1] %= getRegisterValue($op2, $pId);
                break;

            case "jgz":
                if (getRegisterValue($op1, $pId) > 0) {
                    $i += getRegisterValue($op2, $pId) - 1; // -1 because the for loop does +1 (I could also
                }
                break;

            default:
                var_dump($instructions[$i]);
                exit("unknow instruction: $i (pid=$pId)");
        }
    }

    return -2;
}

$lastInstrId = [-1, -1];
$j = count($instructions) * 2 + 1; // just a protection against infinite loop

while ($j-- > 0 && $lastInstrId[0] !== -2 && $lastInstrId[1] !== -2) { // -2 === end
    if ($lastInstrId[0] !== -2 && ($lastInstrId[0] < 0 || !empty($queues[0]))) {
        // do not resume the generator if it waits to receive a value
        // and its queue is still empty
        $lastInstrId[0] = run(0, $lastInstrId[0]);
    }

    if ($lastInstrId[1] !== -2 && ($lastInstrId[1] < 0 || !empty($queues[1]))) {
        $lastInstrId[1] = run(1, $lastInstrId[1]);
    }

    if ($lastInstrId[0] !== -1 && $lastInstrId[1] !== -1 &&
        empty($queues[0]) && empty($queues[1])) {
        // deadlock
        break;
    }
}

echo "Day 18.2: $sendCount ($j)\n"; // 83 wrong
