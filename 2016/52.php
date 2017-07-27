<?php
ini_set("max_execution_time", "60");

$input = "abc";
$input = "wtnhxymk";

$id = 0;
$password = "--------";
$positionsFilled = [];

while ($id < 99999999) {
    $hash = md5($input.$id);
    $id++;
    
    if (substr($hash, 0, 5) === "00000") {
        $pos = $hash[5];
        if (! is_numeric($pos)) {
            continue;
        } else {
            $pos = (int)$pos;
        }

        if ($pos <= 7 && ! in_array($pos, $positionsFilled)) {
            $password[$pos] = $hash[6];

            $positionsFilled[] = $pos;
            if (count($positionsFilled) >= 8) {
                break;
            }
        }
    }
}

echo "result: $password <br>";
