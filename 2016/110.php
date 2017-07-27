<?php

// test input
$floors =[[], // the first array is floor 0, it stays empty
    ["E" => 1, "HM" => 1, "LM" => 1], 
    ["HG" => 1], 
    ["LG" => 1], 
    []
]; 

// real input
$floors_ =[[], 
    ["E" => 1, "TG" => 1, "TM" => 1, "PG" => 1, "SG" => 1], 
    ["PM" => 1, "SM"], 
    ["QG" => 1, "QM" => 1, "RG" => 1, "RM" => 1], // Q = promethium, since P = plutonium
    []
]; 
// each arrays contains string values based on what is in that floor
// E elevator     TG  Thalium Generator   TM  Thalium Microship ...

$elevator = []; // contains the same kind of values based on what is inside the elevator (the human is always presumed present)

// build initial state
// read all inputs and build arrays accordingly

// note that the algorith must gives the MININUM number of steps 


// PSEUDO CODE

/*
analyse floor, check floor up to see what you can take
    you can take a M          if there is no G or the compatible one
    you can take a G          if there is no unpowered M other than the compatible one
    you can take a M and a G  if there is no unpowered M other than the compatible one
always chose tho have the most full elevator but no more than two elements

get up one floor
verify that no M is fried
print state

do same thing to go up again

once on floor 4 
analyse what you can take to go back down
    if not > error
*/


$steps = 0;

$floors =[
    1 => ["E" => 1, "MH" => 1, "ML" => 1], 
    2 => ["GH" => 1], 
    3 => ["GL" => 1], 
    4 => []
];
$floors =[
    1 => new Floor(1, [new Part("MH"), new Part("ML")]), 
    2 => new Floor(1, [new Part("GH")]),
    3 => new Floor(1, [new Part("GL")]),
    4 => new Floor(1, [])
];
$elevatorFloor = 1;

while (! allObjectsOnFourthFloor() && $steps++ < 9999) {
    var_dump("STEP $steps -----------------------------------------------------------------------------------------");
    var_dump($floors);

    // analyse floor to see what can be pickup to go up
    $dir = 1;
    $floorNb = getElevatorFloor();
    if ($floorNb === 4) {
        $dir = -1;
    }

    $o = getMovableObjects($floorNb, $dir);
    if (is_empty($o)) {
        var_dump("error, no movable object on step $steps");
        var_dump($floors);
        break;
    }

}


// $dir = +1 or -1
function getMovableObjects($floorNb, $dir)
{
    global $floors;

    $nextFloorNb = $floorNb + $dir;
    if ($nextFloorNb > 4 || $nextFloorNb < 1) {
        var_dump("getMovableObjects): wrong direction $floorNb $dir");
        return [];
    }

    $floor = $floors[$floorNb];
    $nextFloor = $floors[$nextFloorNb];

    $allMovables = [];
    $bestMovables = [];
    // find all movable things
    // move M + G if possible or M + M or G + G

    // you can take a M if there is no G or the compatible one
    $ms = $floor->getMicroships();

    foreach ($ms as $m) {
        if (
            ! $nextFloor->hasGenerator() || // no G on next floor
            $nextFloor->hasCompatible($m) // compatible G is on next floor
        ) {
            $allMovables[] = $m;
        }
    }

    // you can take a G (or M + G) if there is no unpowered M other than the compatible one
    $gs = $floor->getGenerators();

    foreach ($gs as $g) {
        if (
            // no unpowered M on next floor
            ! $nextFloor->hasUnpoweredMicroships() ||

            // compatible M is on next floor and is the only unpowered M
            (count($nextFloor->getUnpoweredMicroships()) === 1 &&
            $nextFloor->hasCompatible($g)) 
        ) {
            $allMovables[] = $g;
        }
    }


    // if a M is found, check if there is also the G
    // if not check if there is another M that can be moved

}


function getElevatorFloor()
{
    // global $floors;
    // 
    // foreach ($floors as $floorNb => $floor) {
    //     if (isset($floor["E"])) {
    //         return $floorNb;
    //     }
    // }
    // return -1;
}


function allObjectsOnFourthFloor()
{
    global $floors;

    foreach ($floors as $floorNb => $floor) {
        if ($floorNb < 4 && count($floor) !== 0) {
            return false;
        } 
        if ($floorNb === 4 && count($floor) >= 5) { 
            // if we are still here, it's that the previous floors are empty
            // the condition with count() ensure that there is at least 5 elems
            // the elevator + 2G + 2 M (in the test input)
            return true;
        }
    }
    return false;
}


class Floor
{
    public $nb = -1;
    public $parts = [];

    public function __construct($nb, ...$parts) {
        $this->parts = $parts;
    }

    public function getParts($str = true)
    {
        if ($str) {
            $parts = [];
            foreach ($this->parts as $key => $part) {
                $parts[$key] = (string)$part;
            }
            return $parts;
        }
        return $this->parts;
    }

    public function hasPart($part) 
    {
        return in_array((string)$part, $this->getParts(true));
    }

    public function add($parts)
    {
        foreach ($parts as $part) {
            if ($this->hasPart($part)) {
                $this->parts[] = new Part($part);
            }
        }
    }

    public function remove($parts)
    {
        $oparts = array_flip($this->getParts(true));

        foreach ($parts as $part) {
            unset($oparts[(string)$part]);
        }

        $oparts = array_flip($oparts);
        sort($oparts);

        $this->parts = [];
        foreach ($oparts as $part) {
            $this->parts[] = new Part($part);
        }
    }

    public function getGenerators()
    {
        $parts = [];
        foreach ($this->parts as $part) {
            if ($part->type === "G") {
                $parts[] = $part;
            }
        }
        return $parts;
    }

    public function hasGenerator($part = null)
    {
        if ($part === null) {
            return (count($this->getGenerators()) > 0);
        } else {
            $part = (string)$part;
            $parts = $this->getGenerators();
            foreach ($parts as $_part) {
                if ((string)$_part === $part) {
                    return true;
                }
            }
            return false;
        }
    }

    public function getMicroships()
    {
        $parts = [];
        foreach ($this->parts as $part) {
            if ($part->type === "G") {
                $parts[] = $part;
            }
        }
        return $parts;
    }

    public function hasMicroship($part = null)
    {
        if ($part === null) {
            return (count($this->getMicroships()) > 0);
        } else {
            $part = (string)$part;
            $parts = $this->getMicroships();
            foreach ($parts as $_part) {
                if ((string)$_part === $part) {
                    return true;
                }
            }
            return false;
        }
    }

    public function getUnpoweredMicroships()
    {
        $ms = $this->getMicroships();
        $gs = $this->getGenerators();
        $ums = [];
        foreach ($ms as $m) {
            
            if (! in_array($g, $gs)) {
                $ums[] = $m;
            }
        }
        return $ums;
    }

    public function hasUnpoweredMicroships()
    {
        return (count($this->getUnpoweredMicroships()) > 0);
    }

    public function hasCompatible($part)
    {
        if (is_string($part)) {
            $part = new Part($part);
        }

        $comp = $part->getCompatible();
        if ($comp->type === "G") {
            return $this->hasGenerator($comp);
        } else {
            return $this->hasMicroship($comp);
        }
    }
}

function convertArray($a)
{
    $toString = is_object($a[0]);
    for ($i=0; $i < count($a); $i++) { 
        if ($toString) {
            $a[$i] = (string)$a[$i];
        } else {
            $a[$i] = new Part($a[$i]);
        }
    }
    return $a;
}

class Part
{
    public $type = "G";
    public $elem = "";

    public function __construct($str)
    {
        if (is_object($str)) {
            $str = (string)$str;
        }
        $this->type = $str[0];
        $this->elem = $str[1];
    }

    public function getCompatible($str = false)
    {
        $type = "G";
        if ($this->type === "G") {
            $type = "M";
        }
        return new Part($type.$this->elem);
    }

    public function __tostring()
    {
        return $this->type.$this->elem;
    }

    // $part can be object or string
    public function isEqualToPart($part)
    {
        return ((string)$this === (string)$part);
    }
}

// class Microship extends Part
// {
    
// }

// class Generator extends Part
// {

// }

// Game::run();

echo "days 11.1 ".Game::$steps." <br>";