<?php
$res = fopen("80_input.txt", "r");

$screen = [str_repeat(".", 50), str_repeat(".", 50), str_repeat(".", 50), str_repeat(".", 50), str_repeat(".", 50), str_repeat(".", 50)];
// $screen = [str_repeat(".", 7), str_repeat(".", 7), str_repeat(".", 7)];

while (($line = fgets($res)) !== false) {
    
    $matches = [];
    $match = preg_match("/^rect ([0-9]+)x([0-9]+)$/", $line, $matches);
    if ($match === 1) {

        for ($x=0; $x < (int)$matches[1]; $x++) { 
            for ($y=0; $y < (int)$matches[2]; $y++) { 
                $screen[$y][$x] = "#";
            }
        }

        // var_dump($screen);
        continue;
    } 
        

    $match = preg_match("/^rotate column x=([0-9]+) by ([0-9]+)$/", $line, $matches);
    if ($match === 1) {
        $x = (int)$matches[1];
        $count = (int)$matches[2];

        $height = count($screen);
        $oldChars = [];
        for ($y=0; $y < $height; $y++) { 
            $oldChars[$y] = $screen[$y][$x];
        }

        for ($y=0; $y < $height; $y++) { 
            $oldKey = $y-$count;
            if ($oldKey < 0) {
                $oldKey += $height;
            }
            $screen[$y][$x] = $oldChars[$oldKey];
        }

        // var_dump($screen);
        continue;
    }

    $match = preg_match("/^rotate row y=([0-9]+) by ([0-9]+)$/", $line, $matches);
    if ($match === 1) {
        $y = (int)$matches[1];
        $count = (int)$matches[2];

        $width = strlen($screen[0]);
        $aline = str_split($screen[$y]);

        for ($i=count($aline)-1; $i >= 0; $i--) { 
            $aline[$i+$count] = $aline[$i];
        }

        $key = 0;
        for ($i=$width; $i < ($width+$count); $i++) { 
            $aline[$key] = $aline[$i];
            $key++;
            unset($aline[$i]);
        }
        $screen[$y] = implode("", $aline);

        continue;
    }
}

$count = 0;
foreach ($screen as $line) {
    $count += substr_count($line, "#");
}

echo "day 8.1: $count <br>";
echo "day 8.2: ZJHRKCPLYJ";
var_dump($screen);
