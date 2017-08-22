<?php

$res = fopen("100_input.txt", "r");

$valuesPerBot = []; // values is the number each bot holds, sorted
$instructionsPerBot = []; // key = bot id    values are ["low" => [ "value" => {value}, "to" => {bot or ouput nummber}], "high" => ...]


while (($line = fgets($res)) !== false) {
    $line = trim($line);

    $matches = [];
    $p = "/^bot ([0-9]+) gives low to (bot|output) ([0-9]+) and high to (bot|output) ([0-9]+)$/";
    if (preg_match($p, $line, $matches) === 1) {
        $botId = (int)$matches[1];

        if (isset($instructionsPerBot[$botId])) {
            var_dump("error, instruction already exists for bot $botId");
        }

        $instructionsPerBot[$botId] = [
            "low" => [
                "to" => $matches[2],
                "toId" => (int)$matches[3]
            ],
            "high" => [
                "to" => $matches[4],
                "toId" => (int)$matches[5]
            ],
        ];
    } else {
        $matches = [];
        $p = "/^value ([0-9]+) goes to bot ([0-9]+)$/";
        preg_match($p, $line, $matches);
        // var_dump($matches);

        $botId = (int)$matches[2];
        if (! isset($valuesPerBot[$botId])) {
            $valuesPerBot[$botId] = [];
        }

        $valuesPerBot[$botId][] = (int)$matches[1];
        sort($valuesPerBot[$botId]);
    }
}


ksort($valuesPerBot);
// var_dump($valuesPerBot);
ksort($instructionsPerBot);
// var_dump($instructionsPerBot);


function processInstructions($botId, &$valuesPerBot, &$instructionsPerBot)
{
    $botValues = $valuesPerBot[$botId];
    if (! isset($instructionsPerBot[$botId])) {
        $valuesPerBot[$botId] = []; // no instructions for that bot, no need to keep the values
        return;
    }

    $instr = $instructionsPerBot[$botId];

    $botGive = $instr["low"];
    $receiver = []; 
    if ($botGive["to"] === "bot") {
        $id = $botGive["toId"];
        if (! isset($valuesPerBot[$id])) {
            $valuesPerBot[$id] = [];
        }
        $receiver = &$valuesPerBot[$id];
    } // if output, receiver stays array as we don't care about outputs

    $receiver[] = $botValues[0];
    sort($receiver);
    unset($receiver); // because of the passing by reference; if receiver was an output, it's GC already but we don't care

    $botGive = $instr["high"];
    $receiver = []; 
    if ($botGive["to"] === "bot") {
        $id = $botGive["toId"];
        if (! isset($valuesPerBot[$id])) {
            $valuesPerBot[$id] = [];
        }
        $receiver = &$valuesPerBot[$id];
    }

    $receiver[] = $botValues[1];
    sort($receiver);
    unset($receiver);

    unset($valuesPerBot[$botId]); // set empty array since this bot has given all its values
    unset($instructionsPerBot[$botId]);
}

$searchedBot = -1;
$outputs = 0;
$loops = 0;

do {
    // $processedInstructions = 0;
                                                                             
    $temp = $valuesPerBot;
    foreach ($temp as $botId => $values) {
        if (count($values) === 2) {
            // var_dump("process instructions for bot $botId");
            // var_dump($values);

            if (in_array(61, $values) && in_array(17, $values)) {
                $searchedBot = $botId;
            }

            processInstructions($botId, $valuesPerBot, $instructionsPerBot, $totalOutput);
        }
    }
}
while (count($instructionsPerBot) > 0 && $loops++ < 9999);

if ($loops >= 9999) {
    var_dump("loop existed because too big");
}

echo "day 100.1: $searchedBot  <br>";
// completely not sure what to do for day 2 here...
