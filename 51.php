<?php

// $input = "abc";
$input = "wtnhxymk";

$password = "";
$id = 0;

while ($id < 9999999) {
    $hash = md5($input.$id);
    $id++;

    // there is no mistake in the text of te exercice !
    // md5 returns hexadecimal strings, so there is no need to convert to hex...
    
    if (substr($hash, 0, 5) === "00000") {
        $password .= $hash[5];
        if (strlen($password) >= 8) {
            break;
        }
    }
}

echo "day 1: $password <br>";
