// http://adventofcode.com/2015/day/2

#include <stdio.h>
#include "common.c"

int main()
{
    char* input = readInput("03");
    char* coords = calloc(10,sizeof(char));
    char c, tmp[5];
    
    int moveCount = strlen(input), i = 0, vc = 1, x = 0, y = 0;
    
    char** visitedCoords = calloc(moveCount, sizeof(char*)); // 10 is max size for each coords
    
    visitedCoords[0] = "0_0";

    while ((c = input[i++]) != '\0') {
        if (c == '<') x -= 1;
        else if (c == '>') x += 1;
        else if (c == '^') y -= 1;
        else if (c == 'v') y += 1;

        coords[0] = '\0';
        strcat(coords, itoa(x, tmp, 10));
        strcat(coords, "_");
        strcat(coords, itoa(y, tmp, 10));

        if (inStrArray(visitedCoords, moveCount, coords) == 0) {
            visitedCoords[vc++] = coords;
            coords = calloc(10, sizeof(char));
        }
    }

    printf("Day 2015.03.1 %d\n", vc);

    // -----------------
    visitedCoords = calloc(moveCount, sizeof(char*)); // 10 is max size for each coords
    visitedCoords[0] = "0_0";
    coords = calloc(10, sizeof(char));
    i = 0;
    vc = 1;
    short isSantasTurns = 0;
    int santaX = 0, santaY = 0, robotX = 0, robotY = 0;
    
    while ((c = input[i++]) != '\0') {
        isSantasTurns = !isSantasTurns;

        if (isSantasTurns) {
            if (c == '<') santaX -= 1;
            else if (c == '>') santaX += 1;
            else if (c == '^') santaY -= 1;
            else if (c == 'v') santaY += 1;

            coords[0] = '\0';
            strcat(coords, itoa(santaX, tmp, 10));
            strcat(coords, "_");
            strcat(coords, itoa(santaY, tmp, 10));
        } else {
            if (c == '<') robotX -= 1;
            else if (c == '>') robotX += 1;
            else if (c == '^') robotY -= 1;
            else if (c == 'v') robotY += 1;

            coords[0] = '\0';
            strcat(coords, itoa(robotX, tmp, 10));
            strcat(coords, "_");
            strcat(coords, itoa(robotY, tmp, 10));
        }       

        if (inStrArray(visitedCoords, moveCount, coords) == 0) {
            visitedCoords[vc++] = coords;
            coords = calloc(10, sizeof(char));
        }
    }

    printf("Day 2015.03.2 %d\n", vc);

    return 0;
}