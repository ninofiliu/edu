# Return-oriented programming

The attacker wants to spawn a shell. A shellcode can be prevented with NX. Return-to-libc can be prevented with ASLR. But if the text section is not randomized, we can reuse bits of code in it:

```
// shellcode
instr1
instr2
instr3
```

```
// payload
addr1
addr2
addr3

// content of .text
addr1   instr1; ret
addr2   instr2; ret
addr3   instr3; ret
```

The basic idea is to chain together addresses of gadgets that, together, have the same effect as using a shellcode.

The countermeasure idea is quite simple: randomize the text itself! This is PIE (position-independent executable). <!-- ? -->