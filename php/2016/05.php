<?php
// http://adventofcode.com/2016/day/5

$input = "wtnhxymk";
// $input = "abc"; // test

$part1Password = "";
$password1Length = 0;
$part2Password = [];
$password2Length = 0;

for ($id = 0; $id < 99999999; $id++) {
    $hash = md5($input . $id);
    // md5() already returns an hexadecimal strings, so there is no need to convert to hex...

    if (substr($hash, 0, 5) === "00000") {
        if ($password1Length < 8) {
            $part1Password .= $hash[5];
            $password1Length++;
        }

        $position = $hash[5];
        if (is_numeric($position)) {
            $position = (int)$position;
            if ($position < 8 && !isset($part2Password[$position])) {
                $part2Password[$position] = $hash[6];
                $password2Length++;
            }
        }

        if ($password1Length === 8 && $password2Length === 8) {
            break;
        }
    }
}

var_dump($id); // 27712456

echo "Day 05.1: $part1Password <br>";

ksort($part2Password);
$part2Password = implode("", $part2Password);
echo "Day 05.2: $part2Password <br>";
