<?php
$test = "";
//$test = "_test";
$resource = fopen("08_input$test.txt", "r");

$registers = []; // key = register name, value = reg value
$allTimeHighestValue = -1;

while (($line = fgets($resource)) !== false) {
    $instr = [];
    $pattern = "/(?<reg>[a-z]+) (?<dir>inc|dec) (?<offset>[0-9-]+) if (?<condReg>[a-z]+) (?<cond>[<>=!]+ [0-9-]+)/";
    preg_match($pattern, $line, $instr);

    if (!isset($registers[$instr["reg"]])) {
        $registers[$instr["reg"]] = 0;
    }
    if (!isset($registers[$instr["condReg"]])) {
        $registers[$instr["condReg"]] = 0;
    }

    $runInstr = eval("return " . $registers[$instr["condReg"]] . " $instr[cond];");
    if ($runInstr) {
        $offset = $instr["offset"];
        if ($instr["dir"] === "dec") {
            $offset = 0 - $offset;
        }
        $registers[$instr["reg"]] += $offset;
        if ($registers[$instr["reg"]] > $allTimeHighestValue) {
            $allTimeHighestValue = $registers[$instr["reg"]];
        }
    }
}

// get largest at the end
$largest = -1;
foreach ($registers as $value) {
    if ($value > $largest) {
        $largest = $value;
    }
}

echo "Day 8.1: $largest\n";
echo "Day 8.2: $allTimeHighestValue\n";