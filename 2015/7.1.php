<?php
$resource = fopen("7_input.txt", "r");

$wires = []; // key = wire name
$lines = [];

while (($line = fgets($resource)) !== false) {
    $line = trim($line);

    $matches = [];
    if (preg_match("/^([0-9]+) -> ([a-z]+)$/", $line, $matches) === 1) {
        $wires[$matches[2]] = (int)$matches[1];
    } else {
        $lines[] = $line;
    }
}

$lineCount = 888888;
while (count($lines) < $lineCount)  {
    $lineCount = count($lines);

    for ($i = 0; $i < $lineCount; $i++) {
        if (! isset($lines[$i])) {
            break;
        }
        $line = $lines[$i];

        $matches = [];
        if (preg_match("/^([a-z]+) -> ([a-z]+)$/", $line, $matches) === 1) {
            if (! isset($wires[$matches[1]])) {
                continue;
            }
            $wires[$matches[2]] = $wires[$matches[1]];
        }

        elseif (preg_match("/^([a-z0-9]+) (AND|OR) ([a-z0-9]+) -> ([a-z]+)$/", $line, $matches) === 1) {
            $left = $matches[1];
            if (is_numeric($left)) {
                $left = (int)$left;
            } else {
                if (! isset($wires[$left])) {
                    continue;
                }
                $left = $wires[$left];
            }

            $right = $matches[3];
            if (is_numeric($right)) {
                $right = (int)$right;
            } else {
                if (! isset($wires[$right])) {
                    continue;
                }
                $right = $wires[$right];
            }

            if ($matches[2] === "AND") {
                $wires[$matches[4]] = $left & $right;
            } else {
                $wires[$matches[4]] = $left | $right;
            }
        }

        elseif (preg_match("/^([a-z]+) (LSHIFT|RSHIFT) ([0-9]+) -> ([a-z]+)$/", $line, $matches) === 1) {
            if (! isset($wires[$matches[1]])) {
                continue;
            }
            if ($matches[2] === "LSHIFT") {
                $wires[$matches[4]] = $wires[$matches[1]] << (int)$matches[3];
            } else {
                $wires[$matches[4]] = $wires[$matches[1]] >> (int)$matches[3];
            }
        }

        elseif (preg_match("/^NOT ([a-z]+) -> ([a-z]+)$/", $line, $matches) === 1) {
            if (! isset($wires[$matches[1]])) {
                continue;
            }
            $binCompl = decbin(~$wires[$matches[1]]); // 64 bits, usually a negative value
            $wires[$matches[2]] = bindec(substr($binCompl, -16, 16)); // reduce to 16 bits
        }

        else {
            var_dump("unknow pattern !", $line);
            exit;
        }

        // the line has been processed, remove it fom the lines to be processed
        array_splice($lines, $i, 1);
    }
}

var_dump($lines);

echo "day 7.1 : ".$wires['a']." <br>";
