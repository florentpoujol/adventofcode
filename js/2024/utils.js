"use strict"

let startTime = 0
export function startTimer()
{
	startTime = Date.now()
}

/**
 * @return {number} the number of milliseconds
 */
export function endTimer()
{
	return Date.now() - startTime
}

export function endDay(day, data)
{
	const ms = endTimer()
	console.log(`End of day ${day} in ${ms} ms: ${data}`)
}

let i = 0
export function dump(...data)
{
	i++
	console.log('dump ' + i + ' -------------------------')
	console.log(...data)
	console.log('-------------------------')
}

export function dd(...data)
{
	dump(...data)
	throw new Error("End of dd()")
}