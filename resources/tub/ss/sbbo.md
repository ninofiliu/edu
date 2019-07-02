# Stack-based buffer overflows

CPU cycle: fetch -> decode -> execute. From the CPU POV, there is no difference between data and instructions. The goal of an attack is usually to place valid instructions in memory and make the IP register point to them.

From source code to execution: compile -> link -> load -> execute. The compilation step depends on the calling convention.

A usual entry point for SBBO is `strcpy(char* dest, const char* src)` which copies untill a 0-byte is found in `src`. This usually leads to writing to `dest` more than what was expected and thus overwriting adjacent areas of memory on the stack, like other variables, or the return address so as hijack the control flow.

Overflow a buffer with `nop-slide:shellcode:address`. The address points to the middle of the nopsled. The shellcode is isually `execve("/bin/sh", {"/bin/sh"}, NULL)` so that a shell is spawned and easy further exploitation can be done, but it can be anything.

The string must not contain any null bytes. Assembly-level tricks can be used, eg replacing `mov 0 rax` by `xor rax rax`, or division by 256 instead of trailing NULL.
