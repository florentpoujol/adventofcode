import time

#read entire file
# f = open("file.txt", "r")
# lines = f.readlines()
# print(lines)

# read file line by line
# f = open("test.py", "r")
# lines = f.readlines()
#
# for line in lines:
#     line = line.replace("\n", "")
#     print(line)

startTime: float = 0

def startTimer() -> None:
    global startTime
    startTime = time.time()

# return the time in seconds, with fractional part
def endTimer() -> float:
    global startTime
    return time.time() - startTime

def endPart(part: str, data: str|int = '') -> None:
    timeInMilliseconds = endTimer() * 1_000
    print(f"End of part {part} in {timeInMilliseconds:.2f} ms: {data}")