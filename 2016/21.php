<?php
// https://adventofcode.com/2016/day/21

$test = ""; $password = "abcdefgh";
//$test = "_test"; $password = "abcde";
$instructions = explode("\n", file_get_contents("21_input$test.txt"));
$matches = [];

$password = str_split($password);
foreach ($instructions as $instruction) {
    if (preg_match("/swap position ([0-9]+) with position ([0-9]+)/", $instruction, $matches) === 1) {
        $pos1 = (int)$matches[1];
        $pos2 = (int)$matches[2];
        $tmp = $password[$pos1];
        $password[$pos1] = $password[$pos2];
        $password[$pos2] = $tmp;
    } elseif (preg_match("/swap letter ([a-z]) with letter ([a-z])/", $instruction, $matches) === 1) {
        $keys1 = array_fill_keys(array_keys($password, $matches[1]), $matches[2]);
        $keys2 = array_fill_keys(array_keys($password, $matches[2]), $matches[1]);
        $password = array_replace($password, $keys1, $keys2);
    } elseif (preg_match("/rotate (left|right) ([0-9]+) steps?/", $instruction, $matches) === 1) {
        $stepsCount = (int)$matches[2];
        $passwordLength = count($password);
        $newPassword = [];
        foreach ($password as $id => $letter) {
            if ($matches[1] === "right") {
                $newId = ($id + $stepsCount) % $passwordLength;
            } else {
                $newId = $id - $stepsCount;
                if ($newId < 0) {
                    $newId += $passwordLength; // lets's hope steps count isn't > passwordLength
                }
            }
            $newPassword[$newId] = $letter;
        }
        ksort($newPassword);
        $password = $newPassword;
    } elseif (preg_match("/rotate based on position of letter ([a-z])/", $instruction, $matches) === 1) {
        $index = array_search($matches[1], $password);
        $stepsCount = $index + 1;
        if ($index >= 4) {
            $stepsCount++;
        }
        $passwordLength = count($password);
        $newPassword = [];
        foreach ($password as $id => $letter) {
            $newId = ($id + $stepsCount) % $passwordLength;
            $newPassword[$newId] = $letter;
        }
        ksort($newPassword);
        $password = $newPassword;
    } elseif (preg_match("/reverse positions ([0-9]+) through ([0-9]+)/", $instruction, $matches) === 1) {
        $pos1 = (int)$matches[1];
        $pos2 = (int)$matches[2];
        $reversed = array_splice($password, $pos1, ($pos2 - $pos1) + 1);
        $reversed = array_reverse($reversed);
        array_splice($password, $pos1, 0, $reversed);
    } elseif (preg_match("/move position ([0-9]+) to position ([0-9]+)/", $instruction, $matches) === 1) {
        $pos1 = (int)$matches[1];
        $pos2 = (int)$matches[2];
        $letter = array_splice($password, $pos1, 1); // actually an array with 1 letter
        array_splice($password, $pos2, 0, $letter);
    }
}

$password = implode("", $password);
echo "Day 21.1: $password\n";

// part 2

$password = "fbgdceah";
//$password = "decab"; // test (also uncomment the test above to get the correct instructions)
$password = str_split($password);

$instructions = array_reverse($instructions);
foreach ($instructions as $instruction) {
    if (preg_match("/swap position ([0-9]+) with position ([0-9]+)/", $instruction, $matches) === 1) {
        // nothing to change here
        $pos1 = (int)$matches[1];
        $pos2 = (int)$matches[2];
        $tmp = $password[$pos1];
        $password[$pos1] = $password[$pos2];
        $password[$pos2] = $tmp;
    } elseif (preg_match("/swap letter ([a-z]) with letter ([a-z])/", $instruction, $matches) === 1) {
        // nothing to change here
        $keys1 = array_fill_keys(array_keys($password, $matches[1]), $matches[2]);
        $keys2 = array_fill_keys(array_keys($password, $matches[2]), $matches[1]);
        $password = array_replace($password, $keys1, $keys2);
    } elseif (preg_match("/rotate (left|right) ([0-9]+) steps?/", $instruction, $matches) === 1) {
        $stepsCount = (int)$matches[2];
        $passwordLength = count($password);
        $newPassword = [];
        foreach ($password as $id => $letter) {
            if ($matches[1] === "left") { // left become right and vice-versa
                $newId = ($id + $stepsCount) % $passwordLength;
            } else {
                $newId = $id - $stepsCount;
                if ($newId < 0) {
                    $newId += $passwordLength; // lets's hope steps count isn't > passwordLength
                }
            }
            $newPassword[$newId] = $letter;
        }
        ksort($newPassword);
        $password = $newPassword;
    } elseif (preg_match("/rotate based on position of letter ([a-z])/", $instruction, $matches) === 1) {
        // for this one, the idea is to find the step count based on the current position of the letter
        // we know that  curPos = (oldPos + (oldPos + 1)) % length   (or oldPos + 2 if oldPos >= 4)
        // without the modulo:   curPos = oldPos x 2 + 1    OR   curPos = oldPos x 2 + 1 - length   when oldPos + 1 >= (length)
        // so                    oldPos = (curPos - 1) / 2  OR   oldPos = (curPos - 1 + length) / 2   same with 2 instead of 1
        // when  oldPos >=
        $index = array_search($matches[1], $password);
        $stepsCount = $index + 1;
        if ($index >= 4) {
            $stepsCount++;
        }
        $passwordLength = count($password);
        $newPassword = [];
        foreach ($password as $id => $letter) {
            $newId = ($id + $stepsCount) % $passwordLength;
            $newPassword[$newId] = $letter;
        }
        ksort($newPassword);
        $password = $newPassword;
    } elseif (preg_match("/reverse positions ([0-9]+) through ([0-9]+)/", $instruction, $matches) === 1) {
        // nothing to change here
        $pos1 = (int)$matches[1];
        $pos2 = (int)$matches[2];
        $reversed = array_splice($password, $pos1, ($pos2 - $pos1) + 1);
        $reversed = array_reverse($reversed);
        array_splice($password, $pos1, 0, $reversed);

    } elseif (preg_match("/move position ([0-9]+) to position ([0-9]+)/", $instruction, $matches) === 1) {
        $pos1 = (int)$matches[2]; // jus change the positions
        $pos2 = (int)$matches[1];
        $letter = array_splice($password, $pos1, 1); // actually an array with 1 letter
        array_splice($password, $pos2, 0, $letter);
    }
}

$password = implode("", $password);
echo "Day 21.2: $password\n";
