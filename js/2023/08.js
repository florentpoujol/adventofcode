"use strict"

// test input
// instructions = 'LR'
// inputNodes = `11A = (11B, XXX)
// 11B = (XXX, 11Z)
// 11Z = (11B, XXX)
// 22A = (22B, XXX)
// 22B = (22C, 22C)
// 22C = (22Z, 22Z)
// 22Z = (22B, 22B)
// XXX = (XXX, XXX)
// `


// init

let nodes = {}
for (let line of inputNodes.split("\n"))
{
	if (line === '') continue;

	let matches = line.match(/^([0-9A-Z]+) = \(([0-9A-Z]+), ([0-9A-Z]+)\)$/)
	nodes[matches[1]] = [matches[2], matches[3]]
}

// --------------------------------------------------
// part 1

startTimer()

let i = 0
let stepCount = 0
let currentNode = 'AAA'

do {
	let direction = instructions[i] === 'L' ? 0 : 1;

	currentNode = nodes[currentNode][direction]
	stepCount++

	if (currentNode === 'ZZZ') {
		break;
	}

	i++
	if (i === instructions.length) {
		i = 0
	}
} while(true);

endDay('08.1', stepCount) // 15517 in 2 ms

// --------------------------------------------------
// part 2

startTimer()


// this is the equivalent of currentNode
/**
 * array<object>
 */
let paths = [];

for (let [key, value] of Object.entries(nodes)) {
	if (key[2] === 'A')
		paths.push(key)
}

i = 0
stepCount = 0

// let lastStepCounts = [0, 0, 0, 0, 0, 0]

do {
	let direction = instructions[i] === 'L' ? 0 : 1;
	for (let [key, path] of Object.entries(paths)) {
		// if (path[2] === 'Z') {
		// 	continue
		// }

		paths[key] = nodes[path][direction]
	}
	stepCount++

	let allZ = true
	for (let [key, path] of Object.entries(paths)) {
		// if (path[2] === 'Z') {
		// 	let lastStepCount = lastStepCounts[key]
		// 	lastStepCounts[key] = stepCount + 1
		// 	console.log("Z reached", key, path, lastStepCounts[key] - lastStepCount)
		// 	// console.log("Z reached", key, path, stepCount)

		// }
		if (path[2] !== 'Z') {
			allZ = false;
			break;
		}
	}
	if (allZ) {
		break;
	}

	i++
	if (i === instructions.length) {
		i = 0
	}

	if (stepCount % 1_000_000 === 0) {
		console.log(stepCount, paths)
	}	
	if (stepCount === 100_000_000) {
		break;
	}	
} while(true);

endDay('08.2', stepCount) // ??
// in theory this is the correct brute-force solution, but it runs for 10s of millions of loops
// some paths, at least the first two are actually cyclic with a fixed step count
// between each time we see a node ending in Z at 13939 and 933913 step respectively
// I assume the step count is even higher than that for the other paths
//
// Looking at Reddit, an efficient solution is to use LCM (Least common multiple) algorithm
// I assume on the loop step count for each paths, 
// but I do not understand why it helps...
