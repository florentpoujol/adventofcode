<?php

$res = fopen("100_input.txt", "r");

$bots = []; // values is the number each bot holds, sorted
$searchedBot = -1;

while (($line = fgets($res)) !== false) {
    $line = trim($line);

    $matches = [];
    $p = "/^bot ([0-9]+) gives low to (bot|output) ([0-9]+) and high to (bot|output) ([0-9]+)$/";
    if (preg_match($p, $line, $matches) === 1) {
        $id = (int)$matches[1];
        if (! isset($bots[$id])) {
            $bots[$id] = [-2, -1];
        }

        $lowId = (int)$matches[3];
        if (! isset($bots[$lowId])) {
            $bots[$lowId] = [];
        }

        $highId = (int)$matches[5];
        if (! isset($bots[$highId])) {
            $bots[$highId] = [];
        }

        if (! isset($bots[$id][0])) {
            $bots[$id][0] = -1;
        }
        if ($matches[2] === "bot") {
            $bots[$lowId][] = $bots[$id][0];
            sort($bots[$lowId]);
        }
        unset($bots[$id][0]);

        if (! isset($bots[$id][1])) {
            $bots[$id][1] = -1;
        }
        if ($matches[4] === "bot") {
            $bots[$highId][] = $bots[$id][1];
            sort($bots[$highId]);
        }
        unset($bots[$id][1]);

        sort($bots[$id]);
        sort($bots);

    } else {
        $matches = [];
        $p = "/^value ([0-9]+) goes to bot ([0-9]+)$/";
        preg_match($p, $line, $matches);
        // var_dump($matches);
        $id = (int)$matches[2];
        if (! isset($bots[$id])) {
            $bots[$id] = [];
        }

        $value = (int)$matches[1];
        $bots[$id][] = $value;
        sort($bots[$id]);

        if ($value == 61 || $value === 17) {
            var_dump($id, $bots, "---------------------");
        }
    }

    foreach ($bots as $id => $values) {
        if (in_array(61, $values) && in_array(17, $values)) {
            $searchedBot = $id;
        }
    }
}

echo "day 100.1: $searchedBot  <br>";
