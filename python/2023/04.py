import utils

# Day 2023.04

f = open("inputs/04.txt", "r")
lines = f.readlines()

# test input
# lines = """Card 1: 41 48 83 86 17 | 83 86  6 31 17  9 48 53
# Card 2: 13 32 20 16 61 | 61 30 68 82 17 32 24 19
# Card 3:  1 21 53 59 44 | 69 82 63 72 16 21 14  1
# Card 4: 41 92 73 84 69 | 59 84 76 51 58  5 54 83
# Card 5: 87 83 26 28 32 | 88 30 70 12 93 22 82 36
# Card 6: 31 18 13 56 72 | 74 77 10 23 35 67 36 11""".split("\n")

for i, line in enumerate(lines):
    lines[i] = line.replace("\n", "")

# --------------------------------------------------
# init

utils.startTimer()

cards: dict[int, dict[str, int|list[int]]] = {}

def cleanList(theList: str) -> list[int]:
    theList = (theList
               .strip()
               .replace("  ", " ") # two spaces by one spaces
               .split(' '))

    return list(map(int, theList))
# end function

for line in lines:
    parts = line.split(':')
    cardId = int(parts[0].replace("Card ", ""))

    lists = parts[1].split('|')
    cards[cardId] = {
        'copies': 1,
        'winning': cleanList(lists[0]),
        'have': cleanList(lists[1]),
    }

utils.endInit()

# --------------------------------------------------
# part 1

utils.startTimer()

totalPoints: int = 0

for cardId in cards:
    card: dict = cards[cardId]
    winningList = [value for value in card['winning'] if value in card['have']]

    points: int = 0
    for intersection in winningList:
        if points == 0:
            points = 1
            continue
        points *= 2

    totalPoints += points

utils.endPart('1', totalPoints) # 13


# --------------------------------------------------
# part 2

utils.startTimer()

totalCardsCount: int = 0
for cardId in cards:
    card: dict = cards[cardId]
    winningList = [value for value in card['winning'] if value in card['have']]
    totalCardsCount += card['copies']

    for i in range(cardId + 1, cardId + len(winningList) + 1):
        nextCard: dict = cards[i]
        nextCard['copies'] += card['copies']

# much simpler than the PHP version !
utils.endPart('2', totalCardsCount) # 13261850
