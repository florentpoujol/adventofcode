<?php
// http://adventofcode.com/2015/day/19

$test = "";
// $test = "_test"; // test
$resource = fopen("19_input$test.txt", "r");

$molecule = "";
$replacements = [];
$nextValIsStartingMolecule = false;

while (($line = fgets($resource)) !== false) {
    $line = trim($line);
    if (strpos($line, "=>") === false) {
        $molecule = $line;
    } else {
        $parts = explode(" => ", $line);
        if (!isset($replacements[$parts[0]])) {
            $replacements[$parts[0]] = [];
        }
        $replacements[$parts[0]][] = $parts[1];
    }
}


$outputs = []; // string[]
foreach ($replacements as $searchedAtom => $repls) {
    foreach ($repls as $replMolecule) {
        $parts = explode($searchedAtom, $molecule);
        $replacementCount = count($parts) - 1;

        for ($replId = 0; $replId < $replacementCount; $replId++) {
            $output = "";
            foreach ($parts as $id => $part) {
                if ($id < $replacementCount) {
                    if ($id === $replId) {
                        $output .= $part . $replMolecule;
                    } else {
                        $output .= $part . $searchedAtom;
                    }
                } else {
                    // this is the last part
                    $output .= $part;
                }
            }
            if (!in_array($output, $outputs)) {
                $outputs[] = $output;
            }
        }
    }
}

// var_dump($outputs);
$count = count($outputs);

echo "Day 19.1: $count\n";

$eReplacements = $replacements["e"];
unset($replacements["e"]);

$sourcePerRepl = [];
foreach ($replacements as $value => $repls) {
    foreach ($repls as $repl) {
        $sourcePerRepl[$repl] = $value;
    }
}

// sort by keys, longest first
uksort($sourcePerRepl, function ($key1, $key2) {
    $key1len = strlen($key1);
    $key2len = strlen($key2);
    if ($key1len < $key2len) {
        return 1;
    } elseif ($key1len === $key2len) {
        return 0;
    }
    return -1;
});

// start from the finished product
// loop on the replaced molecule, biggest first then replace them by their atom, giving a smallest molecule
// repeat every time a replacement is done until the molecule is just a electron

$steps = 0;
while ($steps < 9999) { // safeguard
    foreach ($sourcePerRepl as $repl => $source) {
        $count = 0;
        $molecule = str_replace($repl, $source, $molecule, $count);
        if ($count > 0) {
            $steps += $count;
            break;
        }
    }
    if (in_array($molecule, $eReplacements)) {
        $steps++;
        break;
    }
}

echo "Day 19.2: $steps\n";
