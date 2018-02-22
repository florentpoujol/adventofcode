<?php
// http://adventofcode.com/2015/day/21

class Person
{
    public $health = 0;
    public $damage = 0;
    public $armor = 0;
    public $mana = 0;
    public $isBoss;
    public $cost = 0;

    public $shieldRemainingTurns = 0;
    public $poisonRemainingTurns = 0;
    public $rechargeRemainingTurns = 0;



    public $attackPlan = [
        "shield", "poison", "recharge"
    ];

    public function __construct(int $health, int $damage, int $armor, int $mana, bool $isBoss = false)
    {
        $this->health = $health;
        $this->damage = $damage;
        $this->armor = $armor;
        $this->mana = $mana;
        $this->isBoss = $isBoss;
    }

    public function doEffects(Person $boss)
    {
        if ($this->shieldRemainingTurns-- > 0) {
            $this->armor = 7;
        } else {
            $this->armor = 0;
        }
        if ($this->poisonRemainingTurns-- > 0) {
            $boss->health -= 3;
        }
        if ($this->rechargeRemainingTurns-- > 0) {
            $this->mana += 101;
        }
    }

    public function attack(Person $enemy)
    {
        if ($this->isBoss) {
            $dmg = max($this->damage - $enemy->armor, 1);
            $enemy->health -= $dmg;
        } else {
            $attack = "missile";
            if (!empty($this->attackPlan)) {
                $attack = array_shift($this->attackPlan);

            } elseif ($this->mana < (229 + 53) && $this->rechargeRemainingTurns <= 0) {
                $attack = "recharge";
            } elseif ($this->mana >= 113 && $this->shieldRemainingTurns <= 0) {
                $attack = "shield";
            } elseif ($this->mana >= 173 && $this->poisonRemainingTurns <= 0) {
                $attack = "poison";
            }
            // else {
            //     $attack = array_shift($this->attackPlan);
            // }

            switch ($attack) {
                case "missile":
                    $enemy->health -= 4;
                    $this->mana -= 53;
                    $this->cost += 53;
                    break;
                case "drain":
                    $enemy->health -= 2;
                    $this->health += 2;
                    $this->mana -= 73;
                    $this->cost += 73;
                    break;
                case "shield":
                    $this->shieldRemainingTurns = 6;
                    $this->mana -= 113;
                    $this->cost += 113;
                    break;
                case "poison":
                    $this->poisonRemainingTurns = 6;
                    $this->mana -= 173;
                    $this->cost += 173;
                    break;
                case "recharge":
                    $this->poisonRemainingTurns = 5;
                    $this->mana -= 229;
                    $this->cost += 229;
                    break;
                default:
                    exit("error, unknow attack: $attack");
                    break;
            }
        }
    }

    /*private $spells = [
        "shield" => ["mana" => 113, "turns" => 6, "armor" => 7],
        "poison" => ["mana" => 173, "turns" => 6, "damage" => 3],
        "recharge" => ["mana" => 229, "turns" => 5, "manaRegen" => 101],
    ];*/
}


$player = new Person(50, 0, 0, 500, false);
$boss = new Person(58, 9, 0, 0, true);

while ($player->health > 0 && $boss->health > 0) {
    $player->doEffects($boss);
    $player->attack($boss);
    if ($boss->health > 0) {
        $player->doEffects($boss);
        $boss->attack($player);
    }
}

echo "Day 22.1: $player->cost\n"; // 1660 too high
var_dump($player, $boss);
