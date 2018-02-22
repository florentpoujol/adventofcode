<?php
// http://adventofcode.com/2017/day/12
$test = "";
// $test = "_test";
$resource = fopen("12_input$test.txt", "r");

class Program
{
    /**
     * @var Program[]
     */
    public $neighbours = [];

    public $name = "";

    public function exploreNeighbours($exploredNeighbours = [])
    {
        // Breath First Search
        $exploredNeighbours[] = (int)$this->name;

        foreach ($this->neighbours as $neighbour) {
            if (!in_array((int)$neighbour->name, $exploredNeighbours)) {
                $exploredNeighbours = $neighbour->exploreNeighbours($exploredNeighbours);
            }
        }

        return $exploredNeighbours;
    }
}

/**
 * @var Program[]
 */
$programsPerName = [];

while (($line = fgets($resource)) !== false) {
    $matches = [];
    preg_match("/([0-9]+) <-> (.+)/", $line, $matches);

    $name = $matches[1];
    $neighbours = explode(", ", $matches[2]);

    $program = $programsPerName[$name] ?? new Program();
    $program->name = $name;
    $programsPerName[$name] = $program; // numerical string keys are converted to int

    foreach ($neighbours as $neighbour) {
        $np = $programsPerName[$neighbour] ?? new Program();
        $np->name = $neighbour;
        $programsPerName[$neighbour] = $np;

        // the comparison MUST be strict as PHP will then compare the actual internal instance
        // instead of comparing the value of each properties, which creates in this cases too deep nesting level error
        if (!in_array($program, $np->neighbours, true)) {
            $np->neighbours[] = $program;
        }
        if (!in_array($np, $program->neighbours, true)) {
            $program->neighbours[] = $np;
        }
    }
}

// Breath First Search
$program = $programsPerName["0"];
$exploredNeighbours = $program->exploreNeighbours();
$count = count($exploredNeighbours);

echo "Day 12.1: $count\n";

$programs = array_values($programsPerName);
$groupCount = 0;

$i = 0;
while (count($programs) > 0 && ++$i < 999) {
    $groupCount++;
    $exploredNeighbours = $programs[0]->exploreNeighbours();

    foreach ($exploredNeighbours as $neighbour) {
        unset($programsPerName[$neighbour]);
    }
    $programs = array_values($programsPerName);
}

if ($i === 999) {
    exit("Error: infinite loop");
}

echo "Day 12.2: $groupCount\n";
