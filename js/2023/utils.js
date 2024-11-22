"use strict";

let startTime
function startTimer() 
{
	startTime = Date.now()
}

/**
 * @return {number} the number of milliseconds
 */
function endTimer()
{
	return Date.now() - startTime
}

function endDay(day, data) 
{
	const ms = endTimer()
	console.log(`End of day ${day} in ${ms} ms: ${data}`)
}


function dump(...data)
{
	console.log('dump -------------------------')
	console.log(...data)
	console.log('-------------------------')
}

function dd(...data)
{
	dump(...data)
	throw new Error("End of dd()");
}