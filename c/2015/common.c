#include <stdlib.h>
#include <stdio.h>
#include <string.h>

char* readInput(char* name) 
{
    char fullName[20] = "input\\";
    strcat(fullName, name);
    strcat(fullName, ".txt");

    FILE* f = fopen(fullName, "r");
    fseek(f, 0, SEEK_END);
    int length = ftell(f);
    rewind(f);

    char* input = malloc(length * sizeof(char));
    char c;
    int i = 0;

    while ((c = fgetc(f)) != EOF) {
        input[i++] = c;
    }
    input[i] = '\0';

    // fclose(f); // make some scripts crash when run not in the debugger...
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

    char** array = malloc(*arraySize * sizeof(char*));
    char* line = malloc(lineSize * sizeof(char));
    char c;
    int i = 0, j = 0, k = 0;
    while ((c = str[i++]) != '\0') {
        if (c == delimiter) {
            line[k] = '\0';
            k = 0;
            array[j++] = line;
            line = malloc(lineSize * sizeof(char));
        } else {
            line[k++] = c;
        }
    }
    line[k] = '\0';
    array[j] = line;

    return array;
}
char** strSplit2(char* str, char delimiter) 
{
    int arraySize;
    return strSplit(str, delimiter, 20, &arraySize);
}

char** readInputAsList(char* name, int* arraySize) 
{
    char* input = readInput(name);
    return strSplit(input, '\n', 50, arraySize);
}

int inStrArray(char** array, int arraySize, char* needle)
{
    for (int i = 0; i < arraySize; i++) {
        char* haystack = array[i];
        if (haystack == 0) break;
        if (strcmp(haystack, needle) == 0) {
            return 1;
        }
    }
    return 0;
}

// char* intToString(int num)
// {
//     // char* output = malloc(10 * sizeof(char));
//     char* output[10];
//     return itoa(num, output, 10);
// }
