
import * as utils from "./utils.js"
import {input} from "./inputs/06.js"
let mapStr = input

// Day 2024.06

// test input
// mapStr =
// `....#.....
// .........#
// ..........
// ..#.......
// .......#..
// ..........
// .#..^.....
// ........#.
// #.........
// ......#...`


// --------------------------------------------------
// init

utils.startTimer()

const map = mapStr.split('\n')

/**
 * @typedef Guard
 * @property {number} x
 * @property {number} y
 * @property {string} symbol
 */

/** @type Guard */
let guard = {
    x: 0,
    y: 0,
    symbol: '',
}

for (const y in map) {
    for (const x in map[y]) {
        const char = map[y][x]
        if (char !== '.' && char !== '#') {
            guard.x = parseInt(x) // why TF are indexes in forin loops strings...
            guard.y = parseInt(y) // and why TF do I bother with typing my object if PHPStorm doesn't alert me of this...
            guard.symbol = char
            break
        }
    }

    if (guard.symbol !== '') {
        break
    }
}

const startingGuard = {...guard}

utils.endInit()


// --------------------------------------------------
// part 1

utils.startTimer()

const mapWidth = map[0].length
const mapHeight = map.length

/** @type {Object<string, boolean>} Keys are like "x_y", values are just true */
let positions = {}
const startingPosition = `${guard.x}_${guard.y}`
positions[startingPosition] = true


const forwardOffsets = {
    '^': { x: 0, y: -1 },
    '>': { x: 1, y: 0 },
    'V': { x: 0, y: 1 },
    '<': { x: -1, y: 0 },
}

while (true) { // we assume there is no loops in the path...
    // get next move
    const forwardOffset = forwardOffsets[guard.symbol]

    const forwardY = guard.y + forwardOffset.y
    const forwardX = guard.x + forwardOffset.x
    if (forwardX < 0 || forwardX >= mapWidth || forwardY < 0 || forwardY >= mapHeight) {
        break
    }

    // rotate 90° right if blocked
    if (map[forwardY][forwardX] === '#') {
        switch (guard.symbol) {
            case '^': guard.symbol = '>'; break
            case '>': guard.symbol = 'V'; break
            case 'V': guard.symbol = '<'; break
            case '<': guard.symbol = '^'; break
        }

        continue
    }

    // else move forward to that tile and record new position
    guard.x = forwardX
    guard.y = forwardY
    positions[`${guard.x}_${guard.y}`] = true
}

utils.endPart('1', Object.keys(positions).length) // 4939


// --------------------------------------------------
// part 2

utils.startTimer()

// so here we try the brute force solution
// we know the new obstacle MUST be on the path the guard usually takes
// a loop is detected when the guard come back to a tile it already visited, *in the same direction*.

/**
 * @param {array<array<string>>} map
 * @param {Object<string, array<string>>} positions
 * @param {Guard} guard
 *
 * @return boolean
 */
function pathIsALoop(map, positions, guard)
{
    const mapWidth = map[0].length
    const mapHeight = map.length

    const forwardOffsets = {
        '^': { x: 0, y: -1 },
        '>': { x: 1, y: 0 },
        'V': { x: 0, y: 1 },
        '<': { x: -1, y: 0 },
    }

    while (true) {
        // get next move
        const forwardOffset = forwardOffsets[guard.symbol]

        const forwardY = guard.y + forwardOffset.y
        const forwardX = guard.x + forwardOffset.x
        if (forwardX < 0 || forwardX >= mapWidth || forwardY < 0 || forwardY >= mapHeight) {
            return false
        }

        // rotate 90° right if blocked
        const tileAhead = map[forwardY][forwardX]
        if (tileAhead === '#' || tileAhead === '0') {
            switch (guard.symbol) {
                case '^': guard.symbol = '>'; break
                case '>': guard.symbol = 'V'; break
                case 'V': guard.symbol = '<'; break
                case '<': guard.symbol = '^'; break
            }

            continue
        }

        // else move forward to that tile and record new position
        guard.x = forwardX
        guard.y = forwardY

        const strPos = `${guard.x}_${guard.y}`
        positions[strPos] ??= {}
        if (positions[strPos][guard.symbol] !== undefined) {
            return true
        }

        positions[strPos][guard.symbol] = true
    }
}

let loopCount = 0
delete positions[startingPosition]
for (const obstructionPosition in positions) {
    // copy the map and the guard
    /** @type {array<array<string>>} */
    let mapCopy = []
    for (const line of map) {
        mapCopy.push(Array.from(line))
    }

    // place the obstruction on the map
    const [obstructionX, obstructionY] = obstructionPosition
        .split('_')
        .map(u => parseInt(u))

    mapCopy[obstructionY][obstructionX] = '0' // 0 is the obstruction

    // prepare the list of positions and symbols
    /** @type {Object<string, array<string>>} keys are the positions like "x_y", values are arrays of guard symbols she had at that position */
    let guardSymbolsPerPosition = {}
    guardSymbolsPerPosition[startingPosition] = [startingGuard.symbol]

    if (pathIsALoop(mapCopy, guardSymbolsPerPosition, {...startingGuard})) {
        loopCount++
    }
}

utils.endPart('2', loopCount) // 1434 (in 4106 ms)
