
import * as utils from "./utils.js"
import {input} from "./inputs/10.js"
let mapStr = input

// Day 2024.10

// test input
// 1 trailhead of score 1
// mapStr =
// `0123
// 1234
// 8765
// 9876`

// mapStr =
// `89010123
// 78121874
// 87430965
// 96549874
// 45678903
// 32019012
// 01329801
// 10456732`

// --------------------------------------------------
// init

utils.startTimer()

/** @type {Array<string>} */
const map = []

for (const line of mapStr.split('\n')) {
    map.push(line)
}

const mapHeight = map.length
const mapWidth = map[0].length

/**
 *
 * @param {Object<string, Array<string>>} pathsPerTrailhead
 * @param {string} trailHeadCoords
 */
function drawPaths(pathsPerTrailhead, trailHeadCoords = undefined)
{
    let oftrailhead = ''
    if (trailHeadCoords !== undefined) {
        oftrailhead = 'of ' + trailHeadCoords + ' '
    }
    console.log('Drawing paths ' + oftrailhead + '-----------------')

    for (const [trailHead, paths] of Object.entries(pathsPerTrailhead)) {
        if (trailHeadCoords !== undefined && trailHeadCoords !== trailHead) {
            continue
        }

        for (const path of paths) {
            let map = [];
            let i = 0
            for (const coordsStr of path) {
                const [x, y] = coordsStr.split('_').map(v => parseInt(v))
                map[y] ??= []
                map[y][x] = i
                i++
            }

            for (let y = 0; y < mapHeight; y++) {
                map[y] ??= []

                for (let x = 0; x < mapWidth; x++) {
                    map[y][x] ??= '.'
                }

                map[y] = map[y].join(' ')
            }

            console.log(map.join('\n'))
            console.log('----------')
        }
    }
    console.log('-------------------------------')
}

utils.endInit()


// --------------------------------------------------
// part 1

utils.startTimer()
let score = 0
/**
 * @typedef Position
 * @property {number} x
 * @property {number} y
 * @property {number} height
 * @property {string} trailhead
 * @property {Array<string>} path
 *
 * @type {Array<Position>}
 */
const positionsToEvaluate = []

for (let y = 0; y < mapHeight; y++) {
    for (let x = 0; x < mapWidth; x++) {
        const height = parseInt(map[y][x])
        if (height === 0) {
            positionsToEvaluate.push({x, y, height, trailhead: `${x}_${y}`, path: [`${x}_${y}`]})
        }
    }
}

const directions = [
    {x: 0, y: -1}, // top
    {x: 1, y: 0}, // right
    {x: 0, y: 1}, // bottom
    {x: -1, y: 0}, // left
]

/** @type {Object<string, Set<string>>} keys are trailhead coords, values are array of unique top coords */
const nineCoordsPerTrailhead = {}

/** @type {Object<string, Array<Array<string>>>} */
const pathsPerTrailhead = {}

do {
    /** @type {Position} */
    const previousPosition = positionsToEvaluate.pop()

    for (const direction of directions) {
        const y = previousPosition.y + direction.y
        if (y < 0 || y >= mapHeight) {
            continue
        }

        const x = previousPosition.x + direction.x;
        if (x < 0 || x >= mapWidth) {
            continue
        }

        const height = parseInt(map[y][x])

        if (height !== previousPosition.height + 1) {
            continue
        }

        if (height < 9) {
            positionsToEvaluate.push({
                x, y, height,
                trailhead: previousPosition.trailhead,
                path: [...previousPosition.path, `${x}_${y}`]
            });

            continue
        }

        // height is 9

        const nineCoords = `${x}_${y}`;

        nineCoordsPerTrailhead[previousPosition.trailhead] ??= new Set()
        nineCoordsPerTrailhead[previousPosition.trailhead].add(nineCoords)

        pathsPerTrailhead[previousPosition.trailhead] ??= [];
        const path = [...previousPosition.path, nineCoords]
        // if (path.length !== 10) {
        //     utils.dd('SHORT PATH', path, x, y, previousPosition.trailhead)
        // }
        pathsPerTrailhead[previousPosition.trailhead].push(path)
    }
} while(positionsToEvaluate.length > 0)

// drawPaths(pathsPerTrailhead, '2_0')

for (const trailheadCoord in nineCoordsPerTrailhead) {
    const localScore = nineCoordsPerTrailhead[trailheadCoord]
    // utils.dump(trailheadCoord, localScore)
    score += localScore.size
}

utils.endPart('1', score) // 682


// --------------------------------------------------
// part 2

utils.startTimer()

const ratingsSum = Object.values(pathsPerTrailhead).reduce((acc, v) => acc + v.length, 0)

utils.endPart('2', ratingsSum) // 1511