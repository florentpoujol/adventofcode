<?php
$resource = fopen("6_input.txt", "r");

$grid = [];
for ($x=0; $x<1000; $x++) {
    if (! isset($grid[$x])) {
        $grid[$x] = [];
    }

    for ($y=0; $y<1000; $y++) {
        if (! isset($grid[$x])) {
            $grid[$x][$y] = [];
        }

        $grid[$x][$y] = 0;
    }
}

while (($line = fgets($resource)) !== false) {
    $line = trim($line);

    $matches = [];
    if (preg_match("/turn (on|off) ([0-9,]+) through ([0-9,]+)/", $line, $matches) !== 1) {
        preg_match("/(toggle) ([0-9,]+) through ([0-9,]+)/", $line, $matches);
    }

    $mode = $matches[1];
    $value = 0;
    if ($mode === "on") {
        $value = 1;
    }
    $from = explode(",", $matches[2]);
    $from[0] = (int)$from[0];
    $from[1] = (int)$from[1];
    $to = explode(",", $matches[3]);
    $to[0] = (int)$to[0];
    $to[1] = (int)$to[1];

    for ($x=$from[0]; $x <= $to[0]; $x++) {
        for ($y=$from[1]; $y <= $to[1]; $y++) {
            if ($mode === "toggle") {
                $grid[$x][$y] += 2;
            } elseif ($mode === "on") {
                $grid[$x][$y]++;
            } elseif ($mode === "off") {
                $grid[$x][$y]--;
                if ($grid[$x][$y] < 0) {
                    $grid[$x][$y] = 0;
                }
            }
        }
    }
}

$brightness = 0;
for ($x=0; $x<1000; $x++) {
    for ($y=0; $y<1000; $y++) {
        $brightness += $grid[$x][$y];
    }
}

echo "day 6.2: $brightness <br>";
