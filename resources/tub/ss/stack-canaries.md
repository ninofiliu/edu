# Stack canaries

If the attacker can't overwrite the return address, they won't be able to hijack the control flow. Stack canaries are a software-level feature to detect such SBBOs:

```
...
return address
old RBP
canary
buffer
arg
... <- SP
```

A canary is placed on the stack before the return address. Before popping the return address, the canary is checked.

The canary must:
- not be accessible: zero-out the used registers after canary write
- not be guessable: high entropy
- not be string-readable: begins by a null
- prevents the overwriting of the return address if possible: contains a null

Issues:
- must be enabled at compile-time
- performance tradeoff
- detect overflow, but weak at preventing one
- useless if an attacker can know its value

Attacker can get a hold of the value by information leaks, for example with format string vulnerabilities. In `printf("%x...%x")`, `printf` will be called as `printf("%x...%x", a1, ..., an)`. For a small i, ai will be a register, but then it will fetch arguments from the stack, thus printing the stack upward, stack canary included.
