<?php
// http://adventofcode.com/2015/day/15

$input = <<<EOL
Sprinkles: capacity 2, durability 0, flavor -2, texture 0, calories 3
Butterscotch: capacity 0, durability 5, flavor -3, texture 0, calories 3
Chocolate: capacity 0, durability 0, flavor 5, texture -1, calories 8
Candy: capacity 0, durability -1, flavor 0, texture 5, calories 8
EOL;

$testInput = <<<EOL
Butterscotch: capacity -1, durability -2, flavor 6, texture 3, calories 8
Cinnamon: capacity 2, durability 3, flavor -2, texture -1, calories 3
EOL;
$input = $testInput; // test

$lines = explode(PHP_EOL, $input);

$data = []; // factor per property per ingredient
$matches = [];

foreach ($lines as $line) {
    preg_match("/^([a-z]+).+([0-9-]+).+([0-9-]+).+([0-9-]+).+([0-9-]+).+([0-9-]+)/i", $line, $matches);

    $data[$matches[1]] = [
        "capacity" => (int)$matches[2],
        "durability" => (int)$matches[3],
        "flavor" => (int)$matches[4],
        "texture" => (int)$matches[5],
        "calories" => (int)$matches[6],
    ];
}

//var_dump($data);

// the problem should be split in several exquations
// here we go for "smart" brute force again
