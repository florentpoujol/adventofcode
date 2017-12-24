<?php
// http://adventofcode.com/2017/day/24

$test = "";
// $test = "_test"; // test
$resource = fopen("24_input$test.txt", "r");

$ports = [];
while (($line = fgets($resource)) !== false) {
    $port = explode("/", trim($line));
    $port[0] = (int)$port[0];
    $port[1] = (int)$port[1];
    $ports[] = $port;
}

$maxStrengthsPerLength = [];
$maxLength = 0;

function build(int $lastPinType, int $currentLength, int $currentStrength, array $remainingPorts)
{
    $_remainingPorts = $remainingPorts;

    foreach ($remainingPorts as $id => $port) {
        if ($port[0] === $lastPinType || $port[1] === $lastPinType) {
            array_splice($_remainingPorts, $id, 1)[0];

            $newLastPinType = $port[1];
            if ($port[1] === $lastPinType) {
                $newLastPinType = $port[0];
            }

            $strength = $currentStrength;
            $strength += $port[0];
            $strength += $port[1];

            build($newLastPinType, $currentLength + 1, $strength, $_remainingPorts);

            $_remainingPorts = $remainingPorts;
        }
    }

    // if we are here, either $remainingPorts is empty or none of them are suitable
    // the bridge is over
    if ($currentLength > 0) {
        global $maxStrengthsPerLength, $maxLength;

        if (!isset($maxStrengthsPerLength[$currentLength])) {
            $maxStrengthsPerLength[$currentLength] = 0;
        }
        $maxStrengthsPerLength[$currentLength] = max($maxStrengthsPerLength[$currentLength], $currentStrength);

        $maxLength = max($maxLength, $currentLength);
    }
}

build(0, 0, 0, $ports);

$maxStrength = max(...array_values($maxStrengthsPerLength));
echo "Day 24.1: $maxStrength\n";

$maxStrength = $maxStrengthsPerLength[$maxLength];
echo "Day 24.2: $maxStrength\n";
