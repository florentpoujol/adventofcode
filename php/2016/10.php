<?php
// http://adventofcode.com/2016/day/10

$res = fopen("10_input.txt", "r");

$valuesPerBot = []; // values is the number each bot holds, sorted
$instructionsPerBot = []; // key = bot id    values are ["low" => [ "value" => {value}, "to" => {bot or ouput nummber}], "high" => ...]

$matches = [];
while (($line = fgets($res)) !== false) {
    $pattern = "/^bot ([0-9]+) gives low to (bot|output) ([0-9]+) and high to (bot|output) ([0-9]+)/";
    if (preg_match($pattern, $line, $matches) === 1) {
        $botId = (int)$matches[1];

        if (isset($instructionsPerBot[$botId])) {
            exit("error, instruction already exists for bot $botId");
        }

        $lowToId = (int)$matches[3];
        $highToId = (int)$matches[5];
        $instructionsPerBot[$botId] = [
            "low" => [
                "to" => $matches[2],
                "toId" => $lowToId,
            ],
            "high" => [
                "to" => $matches[4],
                "toId" => $highToId,
            ],
        ];

        if (!isset($valuesPerBot[$lowToId])) {
            $valuesPerBot[$lowToId] = [];
        }
        if (!isset($valuesPerBot[$highToId])) {
            $valuesPerBot[$highToId] = [];
        }

        continue;
    }

    preg_match("/^value ([0-9]+) goes to bot ([0-9]+)/", $line, $matches);
    $botId = (int)$matches[2];
    if (!isset($valuesPerBot[$botId])) {
        $valuesPerBot[$botId] = [];
    }

    $valuesPerBot[$botId][] = (int)$matches[1];
    sort($valuesPerBot[$botId]);
}


ksort($valuesPerBot);
// var_dump($valuesPerBot);
ksort($instructionsPerBot);
// var_dump($instructionsPerBot);

$valuesPerOutput = [];

function processInstructions($botId)
{
    global $valuesPerBot, $instructionsPerBot, $valuesPerOutput;

    if (! isset($instructionsPerBot[$botId])) {
        unset($valuesPerBot[$botId]);
        return;
    }

    $instr = $instructionsPerBot[$botId];
    $botValues = $valuesPerBot[$botId];

    $botGive = $instr["low"];
    $receiver = [];
    $id = $botGive["toId"];
    if ($botGive["to"] === "bot") {
        if (!isset($valuesPerBot[$id])) {
            $valuesPerBot[$id] = [];
        }
        $receiver = &$valuesPerBot[$id];
    } // if output, receiver stays empty array as we don't care about outputs
    else {
        if (!isset($valuesPerOutput[$id])) {
            $valuesPerOutput[$id] = [];
        }
        $receiver = &$valuesPerOutput[$id];
    }

    $receiver[] = $botValues[0]; // suppose here that the receiver has not already two values...
    sort($receiver);
    unset($receiver); // because of the passing by reference

    $botGive = $instr["high"];
    $id = $botGive["toId"];
    $receiver = [];
    if ($botGive["to"] === "bot") {
        if (! isset($valuesPerBot[$id])) {
            $valuesPerBot[$id] = [];
        }
        $receiver = &$valuesPerBot[$id];
    }
    else {
        if (!isset($valuesPerOutput[$id])) {
            $valuesPerOutput[$id] = [];
        }
        $receiver = &$valuesPerOutput[$id];
    }

    $receiver[] = $botValues[1];
    sort($receiver);
    unset($receiver);

    unset($valuesPerBot[$botId]); // set empty array since this bot has given all its values
    unset($instructionsPerBot[$botId]);
}

$searchedBot = -1;
$outputs = 0;
$loops = 999999;

do {
    $temp = $valuesPerBot;
    foreach ($temp as $botId => $values) {
        if (count($values) === 2) {
            if (in_array(61, $values) && in_array(17, $values)) {
                $searchedBot = $botId;
                // break(2); // not needed for part 2
            }
            processInstructions($botId);
        }
    }
}
while (count($instructionsPerBot) > 0 && $loops-- > 0);

if ($loops === 0) {
    var_dump("loop existed because too big");
}

echo "Day 10.1: $searchedBot  <br>";

$value = $valuesPerOutput[0][0] * $valuesPerOutput[1][0] * $valuesPerOutput[2][0];
echo "Day 10.2: $value <br>";
