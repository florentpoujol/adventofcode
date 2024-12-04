import utils
import re

# Day 2023.02

f = open("inputs/02.txt", "r")
lines = f.readlines()

# test input
# lines = """Game 1: 3 blue, 4 red; 1 red, 2 green, 6 blue; 2 green
# Game 2: 1 blue, 2 green; 3 green, 4 blue, 1 red; 1 green, 1 blue
# Game 3: 8 green, 6 blue, 20 red; 5 blue, 4 red, 13 green; 5 green, 1 red
# Game 4: 1 green, 3 red, 6 blue; 3 green, 6 red; 3 green, 15 blue, 14 red
# Game 5: 6 red, 1 blue, 3 green; 2 blue, 1 red, 2 green""".split("\n")

for i, line in enumerate(lines):
    lines[i] = line.replace("\n", "")


# --------------------------------------------------
# part 1

utils.startTimer()

sum: int = 0

for line in lines:
    parts: list[str] = line.split(':')
    gameId: int = int(parts[0].replace("Game ", ""))

    impossible = False

    draws: list[str] = parts[1].split(';')
    for draw in draws:
        regex: re.Pattern[str] = re.compile(r'(\d+) (\w+)')

        colors: list[str] = regex.findall(draw)
        for color in colors:
            count = int(color[0])
            color = color[1]
            if (color == 'red' and count > 12) or (color == 'green' and count > 13) or (color == 'blue' and count > 14):
                impossible = True
                break

        if impossible: break

    if not impossible:
        sum += gameId

utils.endPart('1', sum)


# --------------------------------------------------
# part 2

utils.startTimer()

sum: int = 0

for line in lines:
    parts: list[str] = line.split(':')

    minCountPerColor: dict[str, int] = {
        'red': -1,
        'green': -1,
        'blue': -1,
    }

    draws: list[str] = parts[1].split(';')
    for draw in draws:
        regex: re.Pattern[str] = re.compile(r'(\d+) (\w+)')
        colors: list[str] = regex.findall(draw)
        for color in colors:
            count = int(color[0])
            color = color[1]
            minCountPerColor[color] = max(minCountPerColor[color], count)

    sum += (minCountPerColor['red'] * minCountPerColor['green'] * minCountPerColor['blue'])

utils.endPart('2', sum)
