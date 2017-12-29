<?php
// http://adventofcode.com/2015/day/21

class Person
{
    public $health = 0;
    public $damage = 0;
    public $armor = 0;

    public function __construct(int $health, int $damage, int $armor)
    {
        $this->health = $health;
        $this->damage = $damage;
        $this->armor = $armor;
    }

    public function attack(Person $enemy)
    {
        $dmg = max($this->damage - $enemy->armor, 1);
        $enemy->health -= $dmg;
    }
}


function battle(Person $player, Person $boss): bool
{
    while ($player->health > 0 && $boss->health > 0) {
        $player->attack($boss);
        if ($boss->health > 0) {
            $boss->attack($player);
        }
    }
    return $player->health > 0;
}


$shop = [
    "Weapons" => [],
    "Armor" => [],
    "Rings" => [],
];

$resource = fopen("21_shop.txt", "r");
$matches = [];
$currentItemType= "";

while (($line = fgets($resource)) !== false) {
    if (preg_match("/^(Weapons|Armor|Rings)/", $line, $matches) === 1) {
        $currentItemType = $matches[1];
    }
    elseif (preg_match("/^([a-z]+(?: \+[123])?)[ ]+([0-9]+)[ ]+([0-9]+)[ ]+([0-9]+)/i", $line, $matches) === 1) {
        $shop[$currentItemType][] = [
            "name" => $matches[1],
            "cost" => (int)$matches[2],
            "damage" => (int)$matches[3],
            "armor" => (int)$matches[4],
        ];
    }
}

array_unshift($shop["Armor"], ["name" => "no_armor", "cost" => 0, "damage" => 0, "armor" => 0]);
$shop["ring1"] = $shop["Rings"];
$shop["ring2"] = $shop["Rings"];
unset($shop["Rings"]);
array_unshift($shop["ring1"], ["name" => "no_ring_1", "cost" => 0, "damage" => 0, "armor" => 0]);
array_unshift($shop["ring2"], ["name" => "no_ring_2", "cost" => 0, "damage" => 0, "armor" => 0]);


$minCost = 99999;
$maxCost = -1;
foreach ($shop["Weapons"] as $weapon) {
    foreach ($shop["Armor"] as $armor) {
        foreach ($shop["ring1"] as $ring1Id => $ring1) {
            foreach ($shop["ring2"] as $ring2Id => $ring2) {
                if ($ring1["name"] === $ring2["name"]) {
                    continue;
                }

                $cost = $weapon["cost"] + $armor["cost"] + $ring1["cost"] + $ring2["cost"];

                $damage = $weapon["damage"] + $ring1["damage"] + $ring2["damage"];
                $defense = $armor["armor"] + $ring1["armor"] + $ring2["armor"];
                $player = new Person(100, $damage, $defense);

                $boss = new Person(104, 8, 1); // stats from puzzle input

                if (battle($player, $boss)) {
                    $minCost = min($minCost, $cost);
                } else {
                    $maxCost = max($maxCost, $cost);
                }
            }
        }
    }
}

echo "Day 21.1: $minCost\n";
echo "Day 21.2: $maxCost\n";
