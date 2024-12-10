
import * as utils from "./utils.js"
import {input} from "./inputs/09.js"
let diskMap = input

// Day 2024.09

// test input
// diskMap =
// `2333133121414131402`

// input is 20K characters long

// --------------------------------------------------
// init

utils.startTimer()

/**
 * Using an array because strings are immutable and you can't do str[i] = '{new char}' like in PHP
 * @type {Array<string>}
 */
const disk = [] // this one will be very long

/** @type {Object<int, {start:number, length:number}>} Keys are the file ids */
const files = {}

let fileId = -1
for (let i = 0; i < diskMap.length; i++) {
    const size = parseInt(diskMap[i])

    if (i % 2 === 0) { // I wonder if I better pull a package from npm for that, it's probably cleaner and a better practice right ?
        // this is a file
        fileId++
        files[fileId] = {id: fileId, start: disk.length, length: size}

        for (let j = 0; j < size; j++) {
            disk.push(`${fileId}`)
        }

        continue
    }

    // this is a free space
    for (let j = 0; j < size; j++) {
        disk.push('.')
    }
}

// utils.dump(disk.join(''), files)

const diskForPart2 = Array.from(disk)

/**
 * @param {Array<string> }disk
 * @param {number} start
 * @param {number} length
 */
function getStrChunk(disk, start, length)
{
    return disk.slice(start, start + length).join (' ')
}

utils.endInit()


// --------------------------------------------------
// part 1

utils.startTimer()

// this is the index in the string of the last character that isn't free space
// so that we don't have to traverse the string in reverse from the start every times
let nextIndexFromEnd = disk.length - 1

let checksum = 0
// now traverse the string from the beginning
// and for each free space, pull a file bit from the end
for (let i = 0; i < disk.length; i++) {
    const char = disk[i]
    if (char !== '.') {
        checksum += parseInt(char) * i
        continue
    }

    for (; nextIndexFromEnd >= 0; nextIndexFromEnd--) {
        if (i >= nextIndexFromEnd) {
            break
        }

        const charFromEnd = disk[nextIndexFromEnd];
        if (charFromEnd === '.') {
            continue
        }

        disk[i] = charFromEnd
        disk[nextIndexFromEnd] = '.'
        nextIndexFromEnd--

        checksum += parseInt(charFromEnd) * i
        break
    }

    if (i >= nextIndexFromEnd) {
        break
    }

    // utils.dump(disk.join(''), i, nextIndexFromEnd)
}

// utils.dd(disk.join(''))

utils.endPart('1', checksum) // 6331212425418 (in 2 ms)


// --------------------------------------------------
// part 2

utils.startTimer()

// here we must loop the other way around.
// loop on the files to be moved and find the first place where it can be moved
// utils.dump(diskForPart2.join(' '))

for (let i = Object.keys(files).length - 1; i >= 0; i--) {
    const fileData = files[i]

    let freeSpaceStartIndex = -1
    // find a free space that fits this file
    for (let j = 0; j < diskForPart2.length; j++) {
        if (j > fileData.start) { // we go over the index of the file we want to move, so give up
            freeSpaceStartIndex = -1
            break
        }

        const char = diskForPart2[j];
        if (char === '.') {
            if (freeSpaceStartIndex === -1) {
                freeSpaceStartIndex = j
            }

            continue
        }
        // here we are on a non free-space char

        if (freeSpaceStartIndex === -1) { // free space hasn't started, try next char
            continue
        }

        // or we reached the end of a free space

        // check if big enough
        const freeSpaceLength = j - freeSpaceStartIndex
        if (freeSpaceLength >= fileData.length) {
            // stop the search for free space
            break
        }

        // or continue the search for free space with the next one
        freeSpaceStartIndex = -1
    }

    if (freeSpaceStartIndex === -1) { // no free space big enough found, move to next file
        continue
    }

    const fileLength = fileData.length
    // move the file to the "beginning"
    const fileToAdd = new Array(fileLength).fill(`${i}`)
    diskForPart2.splice(freeSpaceStartIndex, fileLength, ...fileToAdd)

    // remove the file from the "end"
    const fileToRemove =  new Array(fileLength).fill('.')
    diskForPart2.splice(fileData.start, fileLength, ...fileToRemove)
    // utils.dump(diskForPart2.join(' '), freeSpaceStartIndex, i, fileData, fileToAdd, fileToRemove);

    freeSpaceStartIndex = -1
}

// utils.dump(diskForPart2.join(' '))

checksum = 0
for (let i = 0; i < diskForPart2.length; i++) {
    const char = diskForPart2[i]
    if (char !== '.') {
        checksum += parseInt(char) * i
    }
}

utils.endPart('2', checksum) // 6363268339304