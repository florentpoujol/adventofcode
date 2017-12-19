<?php
// http://adventofcode.com/2017/day/16

$line = "abcdefghijklmnop";
// $line = "abcde"; // test
$line = str_split($line);

$moves = explode(",", file_get_contents("16_input.txt"));
// $moves = ["s1", "x3/4", "pe/b"]; // test

foreach ($moves as &$move) {
    $programs = explode("/", substr($move, 1));
    if (is_numeric($programs[0])) {
        $programs[0] = (int)$programs[0];
    }
    if (isset($programs[1]) && is_numeric($programs[1])) {
        $programs[1] = (int)$programs[1];
    }
    $type = $move[0];
    $move = [
        "type" => $type,
        "a" => $programs[0],
        "b" => $programs[1] ?? null,
    ];
}

function dance()
{
    global $moves, $line;

    foreach ($moves as $move) {
        $a = $move["a"];
        $b = $move["b"];

        switch ($move["type"]) {
            case "p":
                $a = array_search($a, $line);
                $b = array_search($b, $line);
                // no break, let fall through case "X" since it's the same code needed

            case "x":
                $tmp = $line[$a];
                $line[$a] = $line[$b];
                $line[$b] = $tmp;
                break;

            case "s":
                $line = array_merge(
                    array_splice($line, 0 - $a),
                    $line
                );
                break;

            default:
                var_dump($move);
                exit("Error, unknown move.");
                break;
        }
    }

    return $line;
}

dance();
$result = implode("", $line);
echo "Day 16.1: $result\n";

// on my computer, performing 10.000.000 dances with the test data takes 32 seconds (more than 2 minutes with input data)
// so the whole thing would take forever...

// compare each new line (after each dance) with the original one
// hopefully we will find it at least twice, which means there is a loop
// and we just need to figure out how many dances are left

$firstLine = $line;

for ($i = 1; $i < 1E9; $i++) { // $i starts at 0 (done for day 1)
    dance();
    if ($line === $firstLine) {
        // $i is the loopSize
        $i *= (int)(1E9 / $i);
    }
}

$result = implode("", $line);
echo "Day 16.2: $result\n";
