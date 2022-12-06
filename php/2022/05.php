<?php

declare(strict_types=1);

require_once 'tools.php';

$handle = fopen('05_moves.txt', 'r');

function readStacks(): array
{
    $initialStacksRaw = file('05_initial_stacks.txt');
    $initialStacksRaw = array_reverse($initialStacksRaw);
    array_shift($initialStacksRaw); // removes stack numbers
    $stacks = [];

    foreach ($initialStacksRaw as $level) {
        $stackId = 0;

        $length = strlen($level);
        for ($stringIndex = 1; $stringIndex < $length; $stringIndex = $stringIndex + 3) {
            ++$stackId;
            $stacks[$stackId] ??= [];

            $crate = substr($level, $stringIndex, 1);
            ++$stringIndex;

            if ($crate === ' ') { // there is no more crates in that stack
                continue;
            }

            $stacks[$stackId][] = $crate;
        }
    }

    return $stacks;
}

startTimer();

/** @var array<array<string>> $stacks */
$stacks = readStacks();

while (($line = trim((string) fgets($handle))) !== '') {
    $matches = [];
    preg_match('/^move (?P<move>\d+) from (?P<from>\d+) to (?P<to>\d+)$/', $line, $matches);

    for ($i = 0; $i < $matches['move']; $i++) {
        $stacks[(int) $matches['to']][] = array_pop($stacks[(int) $matches['from']]);
    }
}

$topCrates = '';
foreach ($stacks as $stack) {
    $topCrates .= array_pop($stack);
}

printDay("05.1 : $topCrates"); // RNZLFZSJH

// --------------------------------------------------

startTimer();

/** @var array<array<string>> $stacks */
$stacks = readStacks();

rewind($handle);
while (($line = trim((string) fgets($handle))) !== '') {
    $matches = [];
    preg_match('/^move (?P<move>\d+) from (?P<from>\d+) to (?P<to>\d+)$/', $line, $matches);

    $offset = count($stacks[(int) $matches['from']]) - (int) $matches['move'];

    $stacks[(int) $matches['to']] = array_merge(
        $stacks[(int) $matches['to']],
        array_splice($stacks[(int) $matches['from']], $offset)
    );
}

$topCrates = '';
foreach ($stacks as $stack) {
    $topCrates .= array_pop($stack);
}

printDay("05.2 : $topCrates"); // CNSFCGJSM

