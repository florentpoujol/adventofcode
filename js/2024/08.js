
import * as utils from "./utils.js"
import {input} from "./inputs/08.js"
let inputStr = input

// Day 2024.08

// test input
// inputStr =
// `............
// ........0...
// .....0......
// .......0....
// ....0.......
// ......A.....
// ............
// ............
// ........A...
// .........A..
// ............
// ............`


// --------------------------------------------------
// init

utils.startTimer()

/** @type {array<string>} */
const map = []
/**
 * @typedef Coords
 * @property {number} x
 * @property {number} y
 *
 * @type {Object<string, Array<{x:number, y:number}>>} keys are node digits, values are their coordinates
 */
const coordsPerNode = {}

const lines = inputStr.split('\n')
for (let y in lines) {
    y = parseInt(y)
    const line = lines[y]
    map.push(line)

    for (let x in line) {
        x = parseInt(x)
        const char = line[x]
        if (char === '.') {
            continue
        }

        coordsPerNode[char] ??= []
        coordsPerNode[char].push({x, y})
    }
}

/**
 *
 * @param {array<string>} map
 * @param {Object<string, {x:number, y:number}>} antiNodes
 * @return void
 */
function drawMap(map, antiNodes)
{
    const lines = []
    for (let y = 0; y < mapHeight; y++) {
        let line = ''
        for (let x = 0; x < mapWidth; x++) {
            if (antiNodes[`${x}_${y}`] !== undefined) {
                line += '# '
                continue;
            }

            line += map[y][x] + ' '
        }

        lines.push(line)
    }

    console.log(lines.join('\n'))
}

utils.endInit()


// --------------------------------------------------
// part 1

utils.startTimer()

const mapHeight = map.length
const mapWidth = map[0].length

/** @type {Object<string, {x:number, y:number}>} */
let antiNodes = []

for (let y = 0; y < mapHeight; y++) {
    for (let x = 0; x < mapWidth; x++) {
        const char = map[y][x]
        if (char === '.') {
            continue
        }

        // loop on all other of this frequencies node
        // and compute one of the antinodes
        for (const otherCoords of coordsPerNode[char]) {
            if (otherCoords.x === x && otherCoords.y === y) {
                continue
            }

            // This gives the antinode coords on the side of the current node from the other one.
            // The other side will be checked when the current node will be "otherCoords".
            const antiX = x + (x - otherCoords.x)
            const antiY = y + (y - otherCoords.y)

            if (antiX < 0 || antiX >= mapWidth || antiY < 0 || antiY >= mapHeight) {
                continue
            }

            antiNodes[`${antiX}_${antiY}`] ??= {x: antiX, y: antiY};
        }
    }
}
// drawMap(map, antiNodes)

utils.endPart('1', Object.keys(antiNodes).length) // 332

// --------------------------------------------------
// part 2

utils.startTimer()

antiNodes = []

for (let y = 0; y < mapHeight; y++) {
    for (let x = 0; x < mapWidth; x++) {
        const char = map[y][x]
        if (char === '.') {
            continue
        }

        // loop on all other of this frequencies node
        // and compute one of the antinodes
        for (const otherCoords of coordsPerNode[char]) {
            if (otherCoords.x === x && otherCoords.y === y) {
                continue;
            }

            antiNodes[`${x}_${y}`] ??= {x, y}

            // This will give the antinodes coords on the side of the current node from the other one.
            // The other side will be checked when the current node will be "otherCoords".

            const diffX = x - otherCoords.x
            const diffY = y - otherCoords.y

            // unlike in part 1, apply the diff until we go out of the map
            let antiX = x
            let antiY = y
            do {
                antiX += diffX
                antiY += diffY

                if (antiX < 0 || antiX >= mapWidth || antiY < 0 || antiY >= mapHeight) {
                    break
                }

                antiNodes[`${antiX}_${antiY}`] ??= {x: antiX, y: antiY};
            } while (true)
        }
    }
}

// drawMap(map, [])
// drawMap(map, antiNodes)

utils.endPart('2', Object.keys(antiNodes).length) // 1174
