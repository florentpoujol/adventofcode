<?php

declare(strict_types=1);

require_once './tools.php';

$handle = fopen('input/07.txt', 'r');
// day 7 : poker

enum Card: int {
    case Ace = 14;
    case King = 13;
    case Queen = 12;
    case Joker = 11;
    case Ten = 10; // Valet
    case Nine = 9;
    case Eight = 8;
    case Seven = 7;
    case Six = 6;
    case Five = 5;
    case Four = 4;
    case Three = 3;
    case Two = 2;

    public static function fromString(string $card): self
    {
        return match ($card) {
            'A' => self::Ace,
            'K' => self::King,
            'Q' => self::Queen,
            'J' => self::Joker,
            'T' => self::Ten,
            '9' => self::Nine,
            '8' => self::Eight,
            '7' => self::Seven,
            '6' => self::Six,
            '5' => self::Five,
            '4' => self::Four,
            '3' => self::Three,
            '2' => self::Two,
            default => throw new Exception("Unknown string card : '$card'"),
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

    private function calculateType(): void
    {
        $cardsPerType = [];
        foreach ($this->cards as $card) {
            $cardsPerType[$card->value] ??= [];
            $cardsPerType[$card->value][] = $card;
        }

        /** @var array<int> $suiteLengths */
        $suiteLengths = [];
        foreach ($cardsPerType as $cards) {
            $suiteLengths[] = count($cards);
        }

        if (in_array(5, $suiteLengths, true)) {
            $this->type = HandType::FiveOfAKind;

            return;
        }

        if (in_array(4, $suiteLengths, true)) {
            $this->type = HandType::FourOfAKind;

            return;
        }

        if (in_array(3, $suiteLengths, true)) {
            if (in_array(2, $suiteLengths, true)) {
                $this->type = HandType::FullHouse;
            } else {
                $this->type = HandType::ThreeOfAKind;
            }

            return;
        }

        if (in_array(2, $suiteLengths, true)) {
            $pairsCount = 0;
            foreach ($suiteLengths as $length) {
                if ($length === 2) {
                    $pairsCount++;
                }
            }

            if ($pairsCount === 2) {
                $this->type = HandType::TwoPairs;
            } else {
                $this->type = HandType::OnePair;
            }

            return;
        }

        if (count($suiteLengths) === 5) {
            $this->type = HandType::HighCard;
        }
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
            $cards .= $card->name . ' ';
        }
        $cards = trim($cards);

        $type = $this->type->name;

        return "cards=[$cards] type=$type bid=$this->bid strength=$this->strength";
    }
}

function debug(array $handsByZeroIndexedRank): never
{
    echo '-------------------------' . PHP_EOL;
    foreach ($handsByZeroIndexedRank as $index => $hand) {
        $rank = $index + 1 ;
        echo "rank = $rank | hand : $hand" . PHP_EOL;
    }
    echo '-------------------------' . PHP_EOL;

    exit();
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

// debug($handsByZeroIndexedRank);

printDay("07.1: $totalWinnings"); // 250347426, in 11.151 ms
// 251111616 is too high
// 250345345 is too low (after fixing incorrect "two pairs" hands)
// 256778213 (too high, third attempt)

// time taken : like two hours

// --------------------------------------------------

rewind($handle);
startTimer();


printDay("07.2: "); // 1
