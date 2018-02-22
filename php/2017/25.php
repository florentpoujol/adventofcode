<?php
// http://adventofcode.com/2017/day/25

$test = "";
// $test = "_test"; // test
$resource = fopen("25_input$test.txt", "r");

$instructions = [];
$matches = [];
$currentState = "";
$currentValue = -1;

while (($line = fgets($resource)) !== false) {
    $line = trim($line);

    if (preg_match("/In state ([A-Z])/", $line, $matches) === 1) {
        $currentState = $matches[1];
        $instructions[$currentState] = [];
    }
    elseif (preg_match("/If the current value is ([01])/", $line, $matches) === 1) {
        $currentValue = (int)$matches[1];
        $instructions[$currentState][$currentValue] = [];
    }
    elseif (preg_match("/(Write|Move|Continue).+([01]|(?:left|right)|[A-Z])\.$/", $line, $matches) === 1) {
        $value = $matches[2];
        if (is_numeric($value)) { // write instruction: 0 or 1
            $value = (int)$value;
        }

        if ($value === "left") { // move instruction
            $value = -1;
        } elseif ($value === "right") {
            $value = 1;
        }

        $instructions[$currentState][$currentValue][strtolower($matches[1])] = $value;
    }

}

$currentState = "A";
$stepsBeforeChecksum = 12172063;
// $stepsBeforeChecksum = 6; // test
$stepCount = 0;
$checksum = 0;

// increase tape length by $count values at the front and back
function expandTape(int $count)
{
    global $tape, $cursor, $tapeLength;
    $tape = array_merge(
        array_fill(0, $count, 0),
        $tape,
        array_fill(0, $count, 0)
    );
    $cursor += $count;
    $tapeLength += $count * 2;
}
// for performance reason start with a lengthy (but empty) tape
// and whenever needed, increase its length by a lot
// so that I don't need to do that every steps the cursor would be out of range
$tape = [];
$tapeLength = 0;
$cursor = 0;
expandTape(500);


do {
    $tapeValue = $tape[$cursor];
    $instr = $instructions[$currentState][$tapeValue];

    $tape[$cursor] = $instr["write"];
    if ($tapeValue === 1 && $tape[$cursor] === 0) {
        $checksum--;
    } elseif ($tapeValue === 0 && $tape[$cursor] === 1) {
        $checksum++;
    }

    $currentState = $instr["continue"];

    $cursor += $instr["move"];
    if ($cursor < 0 || $cursor >= $tapeLength) {
        expandTape(500);
    }
} while (--$stepsBeforeChecksum > 0);
// var_dump($tape);

echo "Day 25.1: $checksum\n";
