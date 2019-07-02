#include <stdio.h>
#include <stdlib.h>
#include <string.h>

int main() {
    char *a;
    char *b;
    char *c;
    char *d;

    // allocates two blocks
    a = malloc(10);
    b = malloc(10);
    printf("a = %p\n", a); // a = 0x5613f85dc260
    printf("b = %p\n", b); // b = 0x5613f85dc280

    // double-free a
    free(a);
    free(a);

    // malloc will pick the latest freed blocks from the free cache
    c = malloc(10);
    d = malloc(10);
    printf("c = %p\n", c); // c = 0x5613f85dc260
    printf("d = %p\n", d); // d = 0x5613f85dc260

    // unexpected behaviors can be exploited
    strncpy(c, "pizza", 6);
    strncpy(d, "fries", 6);
    printf("'%s' is supposed to be 'pizza'\n", c);
    printf("'%s' is supposed to be 'fries'\n", d);

    return 0;
}