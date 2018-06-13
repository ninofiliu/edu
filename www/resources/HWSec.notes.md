# Hardware security

* Everything is on [the HWSec website](http://soc.eurecom.fr/HWSec/).
* Grading: 25% labs, 75% all-documents-allowed exam

| Slides file | completion status |
| --- | --- |
| introduction | done |
| side-channel attacks | done |
| Fault attacks | blocked: need to ask questions |
| Probing attacks | in progress |

## Introduction

### Basic principles

Auguste Kerckhoff wrote several principles for secure telegram communications; some are obsolete, but one still remains a foundation of secure communications:

> **The system must not require secrecy and can be stolen by the enemy without causing trouble - security must reside in keys.**

Additionally, some systems are theoretically completely secure but any system comes down to a hardware implementation, for which there can't be any proof of complete security. Matthew 26:41 states somewhat the same thing:

> **The spirit is willing but the flesh is weak.**

The main physical threats are the following:

* **Side-channel attacks**: syndromes of activity can be monitored
* **Fault attacks**: supply voltage, temperature and other physical variables can be modified
* **Intrusive attacks**: the device can be modified
* **Probe attacks**: monitoring or MITM of busses or memory zones

The basic framework for securing systems is the following:

1. Locate the flaw
2. Find the requirements for an exploit
3. Imagine countermeasures against these requirements
4. Evaluate the countermeasures

Example:

1. Processing a secret data takes time
2. (processing time depends on the data) & (the attacker can measure the processing time)
3. Processing time is made constant by adding dummy clock cycles
4. Data is kept secret but useless computation must be performed. More on this below

Oftentimes countermeasures do not completely prevent attacks but rather increase their costs. A good countermeasure is one that:

* is cheaper than the asset value
* increase the cost of an attack above the asset value

### DES

Symmetric bloc cypher. Feistel scheme on a 56 bits key with 16 rounds.

Analytical attacks exists in theory but are non practical. Brute force attacks are feasible: the $200K deepcrack computer examines the whole key space in around 9 days. Created by IBM (1975) and the NSA (1977). Superseded by AES in 2002 but still in use because easy to software-implement.

Triple DES variant: Ek3 &#8728; Dk2 &#8728; Ek1

DESX variant: k3 &oplus; Ek2 (k1 &oplus; M)

Algorithm description:

```
round(block,key):
	leftBlock, rightBlock = block[0:32], block[32:64]
	leftBlock = leftBlock XOR feistel(rightBlock, key)
	return rightBlock:leftBlock

main(block,key):
	keys = keySchedule(key)
	for i in range(16):
	  block = round(block, keys[i])
	block=block[32:64]:block[0:32]
	return block
```

### RSA

Asymmetric. Public key. 2048-bits key, uncrackable unless factoring big numbers is easier than we think. 

Algorithm description:

```
CHOOSE p,q two large primes
COMPUTE n=p*q
COMPUTE t=(p-1)*(q-1)
CHOOSE e so that 1<e<t and gcd(e,t)=1
COMPUTE d so that (d*e)%t=1

public key: n,e
private key: n,d

encryption: m' = m**e %n
decryption: m = m'**d %d
signature: s = h**d %n
verification: h = s**e %n
```

### Stats math tools

Expected value: E(X)

Approximation of E(X): x=(x1, ..., xN) samples, E(X) &approx; Ebar(X) = avg(x)

Variance: V(X) = E( (X-E(X))^2 ) = E(X^2) - E(X)^2

Approximation of V(X): V(X) &approx; Vbar(X) = avg(x-E(X)) = avg(x-Ebar(X))

X &perp; Y &dRarrow; V(X+Y) = V(X-Y) = V(X)+V(Y)

Standard deviation: SD(X) = sqrt(V(X))

Pearson correlation coefficient: PCC(X,Y) = ( E(XY)-E(X)E(Y) )/( SD(X)SD(Y) )

PCC in [-1,1]. No correlation -> 0, positive correlation -> 1, negative correlation -> -1.



## Side channel attacks

### Intro

Processing is usually correlated with **side channels**, which are physical variables like time, power consumption, electromagnetic radiations, temperature and noise. These can be observed to retrieve secrets and most of the time, information leakage is undetectable (not the case for quantum computing).

Attacks like these have been widely observed:

* 1965: MI5 exploits the click sound of the Hagelin enciphering machine
* 1996: Kocher time attacks against RSA, DH and DSS
* 1999: Kocher power attacks against many more algorithms
* 2003: application of the 1996 attacks against OpenSSL
* 2013: acoustic attacks against GNUPG's implementation of the RSA

### Timing attacks

#### An example: exponentiation of M^D

```python
def expo(m,d):
	# returns m^D
	# d is D in binary: [1,1,0] is 6
	a=1
	for i in range(len(d)):
	  a=a*a
	  if d[i]==1:
	    a=a*m
	return a
```

This implementation of exponentiation is prone to timing attacks, because calculating `a=a*m` takes time, so the total exec time depends on d.

#### Framework of timing attacks

1. Ensure that processing time is data-dependent.
2. Acquisition phase: with a same secret, build a [{input,time},...] database
3. Analysis phase:
	1. Attacker tries to retrieve parts of the secret
	2. Attacker builds a timing model that depends on this part of the secret
	3. Attacker estimates correlation between `TimingModel(input,part)` and `ObservedTime(input)`
	4. The `part` that gives the highest correlation is the most likely to be a good guess in the seccret

### Power attacks

Based on the assumption, true in the CMOS framework, that power consumption and cell output transition are correlated. The power trace of a naive DES implementation can, for example leave a signal that is correlated with:

* Hamming distance of register transitions
* Hamming weights
* Clock spikes

The power attack framework is the same as for timing attacks, but with power models instead of timing models.



## Fault attacks

Hardware is subject to faults that can sometimes be exploited. First described in 1978, but papers on exploitation in 1996. Three types of fault attacks:

* passive: exploit accidental faults
* semi-active: no package removal, but faults are induced
* active: package removal + induced faults

Several ways to induce flaws:

* power supply glitch: can cause the processor to skip or misinterpret instructions
* clock glitch: can cause errors in RAM read or instruction execution
* temperature: can cause random bit flips, can cause write and not read, can cause read and not write
* white light: can cause photoelectric effects
* laser: similar to white light, more expensive but can be pushed further
* X-rays and ion beams: allow an attack without depackaging, very expensive equipment with a complex setup but the focus can be perfect and devices can even be modified

Faults can be either provisional or destructive. Provisional faults are preferred because they allow for several experiments. Some more vocab:

* SEU: single event upset, ie single bit flips, might be permanent if on static parts
* MEU: multiple event upsets, ie several SEUs, usually a drawback for fault attack
* SEL: single event latchup, permanent failure due to a short circuit
* SEB: single event burnout, permanent failure due to the destruction of a transistor
* SEGR: single event gate rupture
* SESB: single event snap back faults, simmilar to latchups

### BDL attack on RSA

Attack:

```
C -> S	m					client sends message
S -> C	(m^d)%n		server sends signed message
									everybody knows n
									attack = find d, so can sign documents
```

Chinese remainder theorem:

```
IF
	p,q coprimes
	(a,b) integers smaller than (p,q)
THEN
	there exists only one integer x smaller than pq such that:
	x%p==a && x%q==b
MORE
	x can be directly found by
	a*q*(q^-1%p)+b*p*(p^-1%q)
	and
	(a-b)*q*(q^-1%p)+b
```

Application to the RSA:

```
sp=m^d %p
sq=m^d %q
s =m^d %pq

we note P=p^-1%q
			  Q=q^-1%p

CRT: s = sp*p*P + sq*q*Q
       = (sp-sq)*q*Q+b
```

Let be S a faulty signature:

```
gcd(s-S) = gcd(qQ(sp-SP),qp)
         = q * gcd(Q(sp-SP,p))
         = q or pq
```

(I noted the snipped above from the slides but tbh it doesn't make any sense. I'm skipping a few slides & I'm asking him about it tomorrow.)



## Probing attacks

### On-chip attacks

#### Techniques

On-chip attacks relies on depackaging and re-connecting in a new package. Usually slightly expensive and resource-consuming.

Reverse engineering can be employed to visually observe the chip under a microscope and reconstruct the layout. ROM bits can also be retrieved: low power lasers can distinguish between open and closed transistors channels. This memory attack is slow and can't be used everytime, but freezing the RAM can help.

On-chip probing requires the use of microprobes. To probe on sub-micro structures, FIB (focused ion beam) must be used. Very expensive (approx. $1M the FIB machine) but allows for very interesting procedures.

Originally designed for chip repairs. It allows for very high resolution imaging and the beams can go as low as 5nm in diameter. This allows for high precision material removal. Used along with platinium or insulator gases, it can also rewire the chip very precisely, allowing for probing and fault inductions.

#### Countermeasures

Note that the fixed cost of a chip is above $500K, so variations on chip circuits is simply not an option when it comes to countermeasures.

If the registers are scattered around the chip, retrieving values is harder.

If the memory content is enciphered, retrieving values is also harder but it shan't impact performance.

Sensors can be placed onto the chip so as to detect intrusion (the intrusions are stated above: extreme temperature, UV, infra red, ionising radiations...). However a power source is needed in order to take action after detection.

Dummy clock cycles can be introduced so as to mess up with the probing, which operates regularly and must be in sync with the clock.

Metal mesh can be put on top of the chip so as to prevent probing by taking action when the mesh is modified. Note that FIB can easily bypass such meshes.

### On-board probing

Game consoles are often the subject of on-board probing: the GameCube's encrypted boot was hacked in 12 months, the Xbox signed bootup and executables was hacked in 4 months, etc.

This is mainly due to the fact that on-board probing is way easier than on-chip probing (gradute-student difficulty level, a few $100 are necessary).

A few passive countermeasures are sometimes hard to overpass:

* High-freq buses
* Components hidden in PCB layers
* BGA (ball-grid array) to prevent pad probing
* Test point removals

However, none of them are theoretically unbreakable.

A popular active countermeasure is the enciphering and integrity checking of the memory bus (CPU-memory) thanks to an on-chip **memory bus bodyguard**. Note that it can have a big effect on performances. Software-assisted bodyguard enables for better performances but are more complex.

Code itself can also be ciphered: it is enciphered once upon writing it in memory, and upon execustion, it is deciphered on the fly inside the chip. Like this, the only infos that can be probed on board are enciphered. It provides for protection and obfuscation, but not integrity (any enciphered instruction can be replaced during an active on-board probing attack). Only affects performance during cache miss.

