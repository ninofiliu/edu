# NX aka Data Execution Prevention

Most SBBOs rely on placing code in the buffer and execute it. The memory of an application is separated in sections, each having a different usage, thus different RWX constraints can be applied to enhance security and reliability, eg:

- Text: r-x
- Data: r--
- Stack and heap: rw-

Basically NX means that a section is either RW, or X, but never both. <!-- ? -->

This can be achieved with the help of virtual memory. Paging is used to create a virtual view for each process: the virtual memory view is `kernel|stack|libs|heap|bss|data|text` and is process-dependent (via one page table per process).

Unfortunately, there is already a lot of legally executable code to which the attacker can jump to, in the text section and shared libraries mainly.

One code reuse vector is the **return to libc**, in which the program control flow is hijacked to call a libc function. Example:
1. Find the address of `libc` (with `cat /proc/self/maps`)
2. Find the offset of `system` (with `nm /usr/lib/libc.so.6 | grep system`)
3. Find the offset of `"/bin/sh"` (with `strings/usr/lib/libc.so.6 | grep /bin/sh`)
4. Find an instruction (aka a **gadget**) that pops the stack into RDI (`pop %rdi; retq;`)
5. Overwrite the stack:
```
...
addr of system()
addr of "/bin/sh"
addr of gadget <- overwritten return address
...
```
