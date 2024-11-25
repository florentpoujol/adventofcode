"use strict"

// Day 10

// test input
// mapStr = `
// 7-F7-
// .FJ|7
// SJLL7
// |F--J
// LJ.LJ`


// init
// transform map in an array of string

/** @type {array<string>} */
let map = []

for (let line of mapStr.split("\n"))
{
	if (line === '') continue;
	
	map.push(line)
}


// --------------------------------------------------
// part 1

startTimer()

// top left is 0,0
let currentX = 0
let currentY = 0
let currentTile = 'S'

// find S
for (let line of map) {
	const index = line.indexOf('S')
	if (index > -1) {
		currentX = index
		break
	}

	currentY++
}

// loop on the 4 tiles around and get the first one that connects and that we didn't already visited
let visitedTiles = [
	[currentX, currentY, 'S', null],
]

// get top
const directions = [
	[0, -1, 'top'],
	[1, 0, 'right'],
	[0, 1, 'bottom'],
	[-1, 0, 'left'],
]

/**
 * Show what other tiles can connect, for each given tiles
 */
const connections = {
	'|': {
		// the values are the pipes that can connect 
		// to the top level key, in that direction
		top: ['|', '7', 'F'],
		right: [],
		bottom: ['|', 'L', 'J'],
		left: [],
	},
	'-': {
		top: [],
		right: ['-', 'J', '7'],
		bottom: [],
		left: ['-', 'L', 'F'],
	},
	'L': {
		top: ['|', '7', 'F'],
		right: ['-', 'J', '7'],
		bottom: [],
		left: [],
	},
	'J': {
		top: ['|', '7', 'F'],
		right: [],
		bottom: [],
		left: ['-', 'L', 'F'],
	},
	'7': {
		top: [],
		right: [],
		bottom: ['|', 'L', 'J'],
		left: ['-', 'L', 'F'],
	},
	'F': {
		top: [],
		right: ['-', '7', 'J'],
		bottom: ['|', 'L', 'J'],
		left: [],
	},
	'S': {
		top: ['|', '7', 'F'],
		right: ['-', '7', 'J'],
		bottom: ['|', 'L', 'J'],
		left: ['-', 'L', 'F'],
	}
}

/**
 * check that the given tile connects to the currentTile when in that direction
 * 
 * @param {string} currentTile
 * @param {string} direction
 * @param {string} tile
 * 
 * @return {bool}
 */
function canGoToTile(currentTile, direction, tile)
{
	if (tile === '.') return false

	try {
		return connections[currentTile][direction].includes(tile)
	} catch(e) {
		dd('error',currentTile, direction , tile)
	}
}

let i = 0
do {
	for (let dirMod of directions)
	{

		let newX = currentX + dirMod[0]
		if (newX < 0 || newX >= map[0].length) {
			continue;
		}

		let newY = currentY + dirMod[1]
		if (newY < 0 || newY >= map.length) {
			continue;
		}

		let alreadyVisitedTile = false
		for (let [oldX, oldY] of visitedTiles) {
			if (oldX === newX && oldY === newY) {
				alreadyVisitedTile = true;
				break
			}
		}
		if (alreadyVisitedTile) continue;

		// if (map[newY] === undefined) {
		// 	dd('undefined map newY', newY, map)
		// }

		let tile = map[newY][newX] // Y before X here, this is OK

		if (tile === 'S') {
			currentTile = 'S' // will exit the while loop
			break
		}

		if (canGoToTile(currentTile, dirMod[2], tile))
		{
			currentTile = tile
			currentX = newX
			currentY = newY
			visitedTiles.push([currentX, currentY, currentTile, dirMod[2]])
			

			break
		}
	}
	i++
	if (i > 20)
		break;
} while (currentTile !== 'S')

// dd(visitedTiles)
let halfPoint = Math.ceil(visitedTiles.length / 2)

endDay('10.1', halfPoint)

// --------------------------------------------------
// part 2
