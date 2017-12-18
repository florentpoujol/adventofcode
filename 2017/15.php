<?php
// http://adventofcode.com/2017/day/15

$previousA = 512;
$previousB = 191;

// $previousA = 65; // test
// $previousB = 8921; // test


$matches = 0;
for ($i = 0; $i < 4E7; $i++) {
    // in the interest of time, don't use function and hardcode as much things as possible
    $previousA = ($previousA * 16807) % 2147483647;
    $previousB = ($previousB * 48271) % 2147483647;

    if (substr(decbin($previousA), -16) === substr(decbin($previousB), -16)) {
        $matches++;
    }
}
// takes 80 seconds to run on my machine

echo "Day 2015.1: $matches\n";


$previousA = 512;
$previousB = 191;

// $previousA = 65; // test
// $previousB = 8921; // test

$matches = 0;
for ($i = 0; $i < 5E6; $i++) {
    while(($previousA = ($previousA * 16807) % 2147483647) % 4 !== 0);
    while(($previousB = ($previousB * 48271) % 2147483647) % 8 !== 0);

    if (substr(decbin($previousA), -16) === substr(decbin($previousB), -16)) {
        $matches++;
    }
}

echo "Day 2015.2: $matches\n";
