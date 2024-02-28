<?php

declare(strict_types=1);

require_once './tools.php';

$handle = fopen('input/07.txt', 'r');
// day 7 : poker

enum Card: int {
    case Ace = 14;
    case King = 13;
    case Queen = 12;
    case Ten = 10; // Valet
    case Nine = 9;
    case Eight = 8;
    case Seven = 7;
    case Six = 6;
    case Five = 5;
    case Four = 4;
    case Three = 3;
    case Two = 2;
    case Joker = 1;

    public static function fromString(string $card): self
    {
        return match ($card) {
            'A' => self::Ace,
            'K' => self::King,
            'Q' => self::Queen,
            'T' => self::Ten,
            '9' => self::Nine,
            '8' => self::Eight,
            '7' => self::Seven,
            '6' => self::Six,
            '5' => self::Five,
            '4' => self::Four,
            '3' => self::Three,
            '2' => self::Two,
            'J' => self::Joker,
            default => throw new Exception("Unknown string card : '$card'"),
        };
    }

    public function toString(): string
    {
        return match ($this) {
             self::Ace => 'A',
             self::King => 'K',
             self::Queen => 'Q',
             self::Ten => 'T',
             self::Nine => '9',
             self::Eight => '8',
             self::Seven => '7',
             self::Six => '6',
             self::Five => '5',
             self::Four => '4',
             self::Three => '3',
             self::Two => '2',
             self::Joker => 'J',
        };
    }
}

enum HandType: int {
    case FiveOfAKind = 7;
    case FourOfAKind = 6;
    case FullHouse = 5;
    case ThreeOfAKind = 4;
    case TwoPairs = 3;
    case OnePair = 2;
    case HighCard = 1;
    case Undefined = 0;
}

final readonly class Hand {
    /** @var array<Card> */
    private array $cards;
    private HandType $type;
    public int $jokerCount;

    /**
     * @var int This is like a "rank", but that is pondered by type and card value, that we can calculate without looking at other card
     */
    private int $strength;

    public function __construct(
        private string $rawCards,
        public int $bid,
    ) {
        /** @var array<string> $stringCardsArray */
        $stringCardsArray = str_split($this->rawCards);
        /** @var array<Card> $cardCardsArray */
        $cardCardsArray = [];
        foreach ($stringCardsArray as $stringCard) {
            $cardCardsArray[] = Card::fromString($stringCard);
        }
        $this->cards = $cardCardsArray;

        $this->calculateType();
        $this->calculateStrength();
    }

    public function getType(): HandType
    {
        return $this->type;
    }

    private function calculateType(): void
    {
        /** @var array<int, Card> $cardsPerType */
        $cardsPerType = []; // without Joker
        $jokerCount = 0;
        foreach ($this->cards as $card) {
            if ($card === Card::Joker) {
                $jokerCount++;
                continue;
            }

            $cardsPerType[$card->value] ??= [];
            $cardsPerType[$card->value][] = $card;
        }

        $this->jokerCount = $jokerCount;

        $hasSuiteOfFive = false;
        $hasSuiteOfFour = false;
        $hasSuiteOfThree = false;
        $hasSuiteOfTwo = false;
        $hasSuiteOfOne = false;

        $pairsCount = 0;

        foreach ($cardsPerType as $cards) {
            $count = count($cards);

            $hasSuiteOfFive = $hasSuiteOfFive || $count === 5;
            $hasSuiteOfFour = $hasSuiteOfFour || $count === 4;
            $hasSuiteOfThree = $hasSuiteOfThree || $count === 3;
            $hasSuiteOfTwo = $hasSuiteOfTwo || $count === 2;
            $hasSuiteOfOne = $hasSuiteOfOne || $count === 1;

            if ($count === 2) {
                $pairsCount++;
            }
        }

        if (
            $jokerCount === 5
            || $hasSuiteOfFive
            || ($jokerCount === 1 && $hasSuiteOfFour)
            || ($jokerCount === 2 && $hasSuiteOfThree)
            || ($jokerCount === 3 && $hasSuiteOfTwo)
            || ($jokerCount === 4 && $hasSuiteOfOne)
        ) {
            $this->type = HandType::FiveOfAKind;

            return;
        }

        if (
            $jokerCount === 4
            || $hasSuiteOfFour
            || ($jokerCount === 1 && $hasSuiteOfThree)
            || ($jokerCount === 2 && $hasSuiteOfTwo)
            || ($jokerCount === 3 && $hasSuiteOfOne)
        ) {
            $this->type = HandType::FourOfAKind;

            return;
        }

        if (
            $jokerCount === 3
            || $hasSuiteOfThree
            || ($jokerCount === 1 && $hasSuiteOfTwo)
            || ($jokerCount === 2 && $hasSuiteOfOne)
        ) {
            if ($hasSuiteOfTwo && !$hasSuiteOfOne) {
                $this->type = HandType::FullHouse;
            } else {
                $this->type = HandType::ThreeOfAKind;
            }

            return;
        }

        // there is no need to check for (jokerCount === 2) below
        // because if there is at least 2 jokers,
        // the last condition above must always be true,
        // and they are part or a full house, or 3, 4 or 5 of a kind

        // There is no "two pairs" that have one or two jokers

        if (
            $hasSuiteOfTwo
            || ($jokerCount === 1 && $hasSuiteOfOne)
        ) {
            if ($jokerCount === 1) {
                $pairsCount++;
            }

            if ($pairsCount === 2) {
                $this->type = HandType::TwoPairs;
            } else {
                $this->type = HandType::OnePair;
            }

            return;
        }

        $this->type = HandType::HighCard;
    }

    public function getStrength(): int
    {
        return $this->strength;
    }

    private function calculateStrength(): void
    {
        if ($this->type === HandType::Undefined) {
            throw new Exception("There can't be undefined type");
        }

        $strength = 0;

        $invertedCards = array_reverse($this->cards);
        // now the least important cards have the lowest index

        foreach ($invertedCards as $index => $card) {
            $weight = 15 ** $index; // here 15 is an empirical value, 10 was too low, but 100 too high
            $strength += ($card->value * $weight);
        }

        $strength += $this->type->value * 1_000_000;

        $this->strength = $strength;
    }

    // for debug
    public function __toString(): string
    {
        $cards = '';
        foreach ($this->cards as $card) {
            $cards .= $card->toString() . ' ';
        }
        $cards = trim($cards);

        $type = $this->type->name;

        return "$type [$cards] $this->strength";
    }
}

/**
 * @param array<Hand> $handsByZeroIndexedRank
 */
function debug(array $handsByZeroIndexedRank, ?HandType $handType = null, int $onlyWithJokerCount = -1): void
{
    foreach ($handsByZeroIndexedRank as $index => $hand) {
        if ($handType !== null && $hand->getType() !== $handType) {
            continue;
        }

        if ($onlyWithJokerCount >= 0 && $hand->jokerCount !== $onlyWithJokerCount) {
            continue;
        }

        $rank = $index + 1 ;
        echo "$rank | $hand" . PHP_EOL;
    }
}

startTimer();

/** @var array<int, Hand> $hands */
$handsByStrength = [];

while (($line = fgets($handle)) !== false) {
    [$cards, $bid] = explode(' ', $line, 2);
    $hand = new Hand($cards, (int) $bid);

    $strength = $hand->getStrength();
    $handsByStrength[$strength] ??= [];
    $handsByStrength[$strength][] = $hand;
}

ksort($handsByStrength);
/** @var array<int, Hand> $handsByZeroIndexedRank */
$handsByZeroIndexedRank = array_merge(...$handsByStrength); // flatten

$totalWinnings = 0;
foreach ($handsByZeroIndexedRank as $rank => $hand) {
    $totalWinnings += ($rank + 1) * $hand->bid;
}

// debug($handsByZeroIndexedRank, HandType::ThreeOfAKind, 1);

printDay("07.2: $totalWinnings"); // 251224870, in 11.911 ms
// 251094597 is too low
// 251545177 is too high (after fixing the condition to properly find all "five of a kind")