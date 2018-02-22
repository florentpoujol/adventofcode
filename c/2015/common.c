#include <stdlib.h>
#include <stdio.h>
#include <string.h>

char* readInput(char* name) 
{
    char fullName[20] = "input/";
    strcat(fullName, name);
    strcat(fullName, ".txt");

    FILE* f = fopen(fullName, "r");
    fseek(f, 0, SEEK_END);
    int length = ftell(f);

    char* input = malloc(length * sizeof(char));
    char c;
    int i = 0;

    while ((c = fgetc(f)) != EOF) {
        input[i++] = c;
    }
    input[i] = '\0';

    fclose(f);
    return input;
}

int strCount(char* str, char needle)
{
    char c;
    int i = 0, count = 0;
    while ((c = str[i++]) != '\0') {
        if (c == needle) {
            count++;
        }
    }
    return count;
}

char** strSplit(char* str, char delimiter, int lineSize, int* arraySize)
{
    *arraySize = strCount(str, delimiter) + 1;
    
    char* line = malloc(lineSize * sizeof(char));
    char** array = malloc(*arraySize * sizeof(line));
    
    char c;
    int strI = 0, lineI = 0, arrayI = 0;
    while ((c = str[strI++]) != '\0') {
        if (c == delimiter) {
            array[arrayI++][lineI] = '\0';
            lineI = 0;
        } else {
            array[arrayI][lineI++] = c;
        }
    }

    return array;
}
char** strSplit2(char* str, char delimiter) 
{
    int arraySize;
    return strSplit(str, delimiter, 50, &arraySize);
}

char** readInputAsList(char* name, int* arraySize) 
{
    char* input = readInput(name);
    return strSplit(input, '\n', 50, arraySize);
}
