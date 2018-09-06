# NEWBIE

## Explorations

```
gdb newbie
(gdb) info functions
```

Some functions have normal names, but some have very unusual ones:
* pple44514841
* f0144567 
* x73nuf8efhaa
* z93mm999asmt

```
gdb newbie

(gdb) disas main
...
cmp	$0x44,%al
jne	...
call	f0144567
...

(gdb) disas f0144567
...
cmp	$0x6f,%al
jne	...
call	z93mm999asmt
...

(gdb) disas z93mm999asmt
...
cmp	$0x6e,%al
jne	...
call	pple44514841
...

(gdb) disas pple44514841
...
cmp	$0x65,%al
jne	...
call	x73nuf8efhaa
...

(gdb) disas x73nuf8efhaa
...
cmp	$0x3f,%al
jne	...
push	$0x8048686	// pointer to string "Congratulations"
call	<puts@plt>	// prints the string above
add	$0x10,%esp
sub	$0xc,%esp
push	$0x0
call	<exit@plt>	// exit(0) -> success
```

It appears that `newbie` successively checks chars, a positive match on all chars makes the program success. The chars, translated from ascii, form the string:

> Done?

Which is the key.
