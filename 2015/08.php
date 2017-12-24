<?php
// http://adventofcode.com/2015/day/8

$resource = fopen("08_input.txt", "r");

$nbStringChars = 0;
$nbMemoryChars = 0;
$nbEncodedChars = 0;
$matches = [];

while (($line = fgets($resource)) !== false) {
    $line = trim($line);
    $lineLength = strlen($line);
    $nbStringChars += $lineLength;

    $encodedLine = '"'.addslashes($line).'"'; // for part 2
    $nbEncodedChars += strlen($encodedLine); // for part 2

    $lineLength -= 2; // "remove" the 2 outer double quotes

    // detect escape sequences
    $count = 0;
    $line = preg_replace("/\\\\\\\\/", "", $line, -1, $count);
    // use preg_replace here so that it does interfere with other pattern
    // like when the string ends with \\"
    $lineLength -= $count;

    preg_match_all('/\\\\"/', $line, $matches);
    $lineLength -= count($matches[0]);

    preg_match_all("/\\\\x[0-9a-f]{2}/", $line, $matches);
    $lineLength -= count($matches[0]) * 3;

    $nbMemoryChars += $lineLength;
}

$total = $nbStringChars - $nbMemoryChars;
echo "Day 8.1: $total <br>";

$total = $nbEncodedChars - $nbStringChars;
echo "Day 8.2: $total <br>";
