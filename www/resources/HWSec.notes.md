# Hardware security

* Everything is on [the HWSec website](http://soc.eurecom.fr/HWSec/).
* Grading: 25% labs, 75% all-documents-allowed exam
* Notes progression: stopped at powerpoint 3/4 slide 0.

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