# Security Applications in Networking and Distributed Systems

* Professor: Refik Molva (refik.molva@eurecom.fr)
* Slides are on my.eurecom.fr


| Outline | completion status |
| --- | --- |
| Access control | done |
| Domain control | done |
| Cryptographic security and the internet | done |
| Wireless security | done |

## Access control (AC)

### Access control model

The model is a matrix of **rights**: `R[i][j]` is the right of the i-th **subject** over the j-th **object**. Each subject has a **capability list (CL)** and each object an **access control list (ACL)**. A **permission** is a `(object,right)` tuple defining the authorized operations on an object.

Decision and enforcement have to be taken according to this rights matrix. There are two strategies:

* **Discretionary access control (DAC)**: the AC decision is left to the owner, ex: UNIX and Active Directory
* **Mandatory access control (MAC)**: the AC is defined by system-wide mandatory rules, ex: TrustedBSD

In some systems, AC is enforced by the subjects sending its CL along with its request; this requires authentication and integrity verification. In other systems, the request is sent alone and the OS enforces the AC thanks to its ACL. There is a flexibility-control trade-off between the two options. Other systems exist, for example the user agent is the framework for Kerberos in Windows:

```
AC agent

	1. subject → agent   authorization request
	2. agent   → subject authorization
	3. subject → object  access request + authorization
```

More generally, the AC framework in the ISO standards is defined by the four components that interact in the following fashion:

```
subject --(request)-- AEF
AEF --(access)-- object
AEF --(decision)-- ADF

AEF: access enforcement function
ADF: access decision function
     inputs: ADI (access decision information) on subject
             ADI on operation
             ADI on object
             AC policy
     output: decision
```

### Role-based access control (RBAC)

In a nutshell, users are associated with roles which are associated with permissions. This reduces the complexity and the length of the process for creating new objects or subjects but the previous matrix model is not enough, rather we have two elements:

* Role array: `S[k]` is the role of the k-th subject
* Rights matrix: `R[i][j]` is the right of the i-th role on the j-th object

There can be *role hierarchies*, *static separation of duty*, *dynamic separation of duty*, *role cardinality contraints*, etc.

**PERMIS** is a java-based RBAC authorization engine.

### PKI

#### X.509

**Public key infrastructure (PKI)** standard that defines the format of public key certificates. Used in TLS/SSL. A certificate contains, amongst other fields:

* Issuer name
* Validity period
* Subject name
* Subject public key algorithm and key value
* Certificate

To get a signed certificate, the process is the following:

```
1. server        generate name, private key, and public key
2. server -> RA  name + public key
3. RA -> CA      name + public key + registration
4. CA -> server  signed certificate
```

To validate a certificate, the process is the following:

```
1. server -> client  signed certificate
2. client -> VA      signed certificate + validation
```

#### SPKI

X.509 certificates are used to check one's identity but SPKI goes even further by allowing to check one's permission to perform a task. Note that the identification of a subject is not always performed, but that's not relevant anyway outside of small communities. In a nutshell, an SPKI certificates says:

> The holder of the private key linked to the public key K has the right R

### AC in windows

Main goal: AC on network files and printers in Windows servers and Active directory (AD).

Objects are files or AD objects. Subjects can be seen as the users, groups, and computers; but a subject is always physically a thread. Windows' AC is discretionary, ie permissions are granted or denied by the object's owner.

Subjects are threads who have **access tokens** containing their username and groups. Objects have a **discretionary access control list (DACL)** which contains several **access control entries (ACEs)**, each of which has an access decision, an SID and an access action. More on ACE and DACL [here](https://msdn.microsoft.com/en-us/library/windows/desktop/aa446597(v=vs.85).aspx).

Along with these, there exists some Windows-specific components:

* **Security principal (SP)**: user, group, or computer. SP have accounts. Local accounts are managed by the security account manager (SAM), domain accounts are managed by Active Directory.
* **Security identifier (SID)**: identifies SP or domain trusts.
* **Access token**: associated with a subject. For a user, created after a log on. SID of user + SID of all groups he belongs to
* **Primary token**: associated to a process. Represents its security subject. Inherited to child processes.
* **Impersonation token**: associated to a thread. Represents a client process' security subject
* **Permission**: right to perform one or many operations on one or many objects.
* **Privilege**: right to perform one or many operations that affects an entire computer.
* **Security descriptor**: data structure containing the security infos associated with a securable object, eg file or printer.

Various authentication methods to create the access token are supported: password, personal token, smartcard, kerberos, NTLM...

### SAML

Security assertion markup language. XML standard for exchanging authentication and authorization data. Defines assertions, protocols, bindings, and profiles. It *does not* enforce anything.

**Assertion examples**: *subject S was authenticated using the algorithm A at the time T*, *subject S is authorized to perform X*. An assertion can be signed but it's not always needed.

The **protocol** defines the structure of queries and responses.

The **binding** defines how SAML messages map onto standard messaging protocols.

SAML involves two types of actors: assessing party (eg single sign-on service), and relying party (eg attribute requester).

### XACML

XML extension language to specify and enforce authorization policies. System overview:

```
[user] --(access request)-- [PEP] --(access)-- [resource]
                              |
                       (XACML req/resp)
                              |
   [PIP] --(attr request)-- [PDP] --(policy)-- [PAP]
```

* **Policy administration point (PAP)**: create and store policies
* **Policy decision point (PDP)**: evaluates the applicable policy
* **Policy enforcement point (PEP)**: performs access control
* **Policy information point (PIP)**: data required for policy evaluation

Request = subject + object + action

Response = permit | permit + obligations | deny | NA | Indeterminate



## Domain control

There exists two main directions for network security. The first is to implement layer 5 (app-to-app) cryptographic security but it doesn't address all the problems.

The other direction is **domain control**. Based on the assumption that some networks are trusted (eg intranet) and some aren't (eg internet), the main idea is to filter the traffic across their border in both direction so that no further protection is required inside the trusted network. Several components:

* packet filtering
* NAT
* app gatways
* circuit gateways

### Packet filtering

Integrated with the packet forwarding components.

For each IP packets, check the packets against a set of rules and forward/discard/log the packet accordingly. Rules are based on infos that can be extracted from the IP header, mainly ip.src, ip.dst, port.src, port.dst, and the flags. Usually the algorithm is implemented in the following fashion, here's a table that authorizes only outgoing mail:

```
table:
  1.    match: ip.src in intranet, port.dst == 25. action: allow
  2.    match: flags contain ack, port.src == 25. action: log
  last. match: *. action: deny (this entry is often implicit)

algorithm:
  packets are processed through the table
  upon a match, the corresponding action is undertaken
  if no match, the packet is denied (aka last entry)
```

This packet filtering can block most unwanted traffic but it is not "strong": cryptographic verification of integrity can't be performed. It can't detect external IP spoofing. IP fragmentation can be exploited (header is in the first fragment so subsequent fragments have to be accepted).

### NAT

Network address translation. Actually most NAT configs are NAT/PAT (port address translation).

First implemented to address the IPv4 address depletion problem. Principle: upon accessing the internet, a machine from a private network get assigned a private address (ex: 10.1.2.3). The machine actually accesses the internet through a NAT/PAT router that translate the private machine's IP and port into the NAT/PAT's public IP and one of its port.

Application-transparent, network-transparent, but adds delay and there exists some techical limitations.

### Application-layer filtering

Performed by application gatway hosts, which are basically proxies (which are different from OS-level VPNs, see [this article](https://www.howtogeek.com/247190/whats-the-difference-between-a-vpn-and-a-proxy/) explaining the difference between the two). Proxies act as middle men for application-specific tasks - it does not redirect the whole traffic like with a VPN.

Strong security can be performed, because in the case of full proxies there are two connexions: machine-proxy and proxy-server, so intelligence can be put in between. It offers:

* isolation of intranet resources
* confinement of vulnerabilities
* network isolation
* possibility of private IP addressing

In order to prevent proxies from being hacked, they are stripped out from non-essential features (eg user accounts, IP forwarding, source routing, some deamons and some files) and hardened (eg extended and remote logging). They then become **bastion hosts** and are not an easy target for attackers.



## Cryptographic security and the internet

### IPSec

#### 101

Several internet layer protocols suffers from flaws:

| protocol | flaws |
| --- | --- |
| IP | unauthorized data disclosure. unauthorized modification. masquerading. flooding. traffic analysis. |
| ARP | DoS. traffic subversion. |
| ICMP | eavesdropping. DoS on routers. |
| IGMP | DoS on users. Impersonation of users. |

IPSec establishes mutual authentication at the beginning of a session a encrypts the traffic thereafter. IPSec is also designed to be protected against replay attacks. IPSec works over the IP (transport-layer protocol).

It can work between two hosts (transport mode), between two gateways (tunnel mode, used in VPN), or even between a host and a gateway:

```
[machine] (secure traffic) /insecure traffic/

host2host:
  [H1]
    (IP|IPSec|data)
  [internet]
    (IP|IPSec|data)
  [H2]

network2network:
  [H1]
    /IP(H1-H2)|data/
  [intranet]
    /IP(H1-H2)|data/
  [Security gateway 1]
    (IP(SG1-SG2)|IPSec|IP(H1-H2)|data)
  [internet]
    (IP(SG1-SG2)|IPSec|IP(H1-H2)|data)
  [Security gateway 2]
    /IP(H1-H2)|data/
  [intranet]
    /IP(H1-H2)|data/
  [H2]

host2network:
  [H1]
    (IPSec|data)
  [internet]
    (IPSec|data)
  [SG]
    (IP|data)
  [intranet]
    (IP|data)
  [H2]
```

In fact, IPSec can be used in even more complex ways, here's a VPN + end-to-end security:

```
[H1] IP.H1 [intranet1] IP.H1 [SG1] IP.SG1 [internet] IP.SG1 [SG2] IP.H1 [intranet2] IP.H1 [H2]
     IPSec.H1          IPSec.H1    IPSec.SG1         IPSec.SG1    IPSec.H1          IPSec.H1
                                   IP.H1             IP.H1
                                   IPSec.H1          IPSec.H1
```

IPSec ensures security thanks to the following components:

| protocol | provides |
| --- | --- |
| AH (authentication header) | integrity, authentication, anti-replays |
| ESP (encapsulating security payload) | integrity, authentication, anti-replays, confidentiality |
| ISAKMP | key exchange and authentication |

#### Key management

ISAKMP (internet security assocation and key management protocol) is a framework for the negotiation of keys and algorithms. It supports several key distribution methods:

* server-based (eg. Kerberos)
* pair-wise (eg. Diffie-Hellman). Then called IKE = internet key exchange = ISAKMP + oakley
* public key certification (eg. x509 or DNSSEC)

And allows for:

* Generation and distribution of symmetrical keys
* Certification of public keys
* Attribute negotiation

The technical term for what ISAKMP accomplishes is **security association (SA)**.

ISAKMP allows for IP spoofing protection, because a server responds to requests that contains IP_client, IP_server, hash(IP_client,IP_server,time,secret_client), and hash(IP_client,IP_server,time,secret_server), request that can be formulated only after a legitimate client-server exchange. The hashes are called **cookies**.



### TLS/SSL

SSL: 1990s, TLS: 2000s. TLS provides:

* peer authentication (server or server+client)
* confidentiality
* integrity
* anti-replay
* session key generation
* parameters negotiation

#### Algorithm

Before they exchange data, the proceed to a key exchange and agreement for the protocol and the public/private key used for the symmetric key exchange. That is when public keys can be certified *(authentication)*.

Application data is then exchanged using the symmetric key *(privacy)* and the content checked with a MAC (message authentication code) *(integrity)*.

#### Protocol

`IP > TCP > (record = (handshake | change cipher | alert | application))`

Record layer building:

1. Data
2. Data is fragmented
3. Fragmented data is compressed
4. MAC is added
5. Encryption is performed
6. record header is added

A TLS session can be reused by several TCP connections.

#### VPN over SSL

IPSec is a good VPN solution but requires a resident IPSec program on the remote terminal. SSL enables an any-to-any solution that also crosses firewalls: traffic from the client and the secure gateway is SSL-encapsulated.

However, there are security exposures that resides in the client's browser, like the history caching. In a nutshell:

| / | VPN/SSL | VPN/IPSec |
| --- | --- | --- |
| Clientless | yes | no |
| Firewall traversal | yes | no |
| IP support | no | yes |
| Client-side secu. exposure | high | low |
| AC by the SG | yes | no |



### Application security

#### DNS

**Resource records (RR)** are IP-name association (can also contain more informations like addresses of mail servers, of name servers, canonical names, etc). A **zone of authority** is a set of names managed by a name server.

The explanation of the DNS protocol is not the subject but here's a reminder:

```
[client]-----[resolver]--+--[master]<--(name data)
                         |     |
                         |     V
                         +--[slaves]
```

There exists multiple vulnerabilities in the protocol that runs over UDP:

* MITM client-resolver
* UDP amplification and reflection
* Data modification on the slave
* Cache poisonning on the resolver
* Spoofing master-slave
* Data modification on the name data input

DNSSEC allows for securing against the three first threats by using publlic key algorithms for signatures of the messages.

#### Routing: RIP, BGP and OSPF

Intra-domain: routing information protocol (RIP), open shortest path first (OSPF). Inter-domain: border gateway protocol (BGP).

The main issue about these protocols is bogus information updating the routing behavior of packets, allowing for traffic hijacking, monitoring, and denial of service, and neither of these protocol seriously implements security.

Autonomous systems exchange reachability infos thrrough BGP. An autonomous system is a set of routers that fall under a single management authority. Router BGP communication over TCP is not protected, allowing for DoS, TCP spoofing, TCP hijacking, false advertisements, no authentication, and no data integrity.

S-BGP (secure BGP) addresses the crux of the problem by using PKI for address attestation (proves AS originates address), route attestation (routes signed from one AS to another), but it is resource intensive. soBGP (secure origin BGP) goes further by offering tradeoffs (verify route before/after accepting connection, verify entire/part of a route, etc).

#### SNMP

Simple network management protocol. Over UDP:161. Used to collect infos and change behavior of devices on a network. Two components: manager and agent.

The main issue resides in the lack of authentication, thus impersonation is possible and can lead to unauthorized disclosure or modification of data.





## Wireless security

### WiFi

IEEE 802.11 is a set of media access control and physical layer specifications for implementing wireless local area network (WLAN) computer communication. Three standards for security: wired equivalent privacy (WEP), wifi protected access (WPA), and robust security network (RSN aka WPA2).

The main threat is the lack of physical protection, allowing for easy and discrete local eavesdropping & MITM. Appropriate equipment can do the same from a few kilometers away.

Since authentication frames are not authenticated (except in 802.11i), MITM is easy.

The extensible authentication protocol (EAP) is an authentication framework (ie not only used in 802.x):

```
S  AP
 <-  identity request
 ->  identity response
 <-  challenge
 ->  response
 <-  accept/deny
```

(S = supplicant, AP = access point)

#### WEP

Encryption:

```
K = shared key
IC = integrity check = h(header.data)
IV = random initialisation vector (in clear in the packet)
k = RC4(K,IV)
m = data.IC
P = plaintext
C = ciphertext = P XOR k
```

Partial known plaintext attack (ciphertext is always assumed to be known):

```
C1 XOR C2 = (P1 XOR RD4(K,IV)) XOR (P2 XOR RD4(K,IV))
          = P1 XOR P2
```

What is known about P1 is also known about P2.

Moreover, it's possible to retrieve the unsalted hash of the wifi password, allowing for rainbow attacks and the likes to gain unauthorized access to the router's services.

#### WPA

Software implementation implemented to secure WEP without hardware changes.

#### RSN aka WPA2

* Encryption by AES (advanced encryption standard, secure, but requires hardware changes for reasonable performances)
* Dynamic authentication and encryption keys

Operations:

```
S   AP   RADIUS
 <->              discovery
 <->  <->         (enterprise mode only) EAP authentication
 <->              key mgmt
 <->              data transfer
 <->              connection termination
```



### GSM

Components:

* MS = mobile subscriber (phone + SIM)
* BS = base station (communicates with the MS)

Requirements:

* Subscriber identity protection
* Subscriber authentication
* No MS masquerade

The radio space is open to all attackers and malicious messages can be transmitted, thus causing:

* SMS with broken headers than can crash phones
* SMS Masquerading through fake caller ID

The 3rd Generation Partnership Project (3GPP) fixed most of the security flaws of GSM. Main features:

* Compatibilit with GSM
* Minimal trust in intermediate components
* Longer keys
* Usual-network mutual authentication





## More

### The iptables command

Excerpt from the man page:

```
OPTIONS
  -A, --append chain rule-specification
    Append rule(s) to the end of the chain

  -F, --flush [chain]
    Flush the chain, or flush all if no chain specified

  -N, --new-chain chain
    Create new chain

  -P, --policy chain target=ACCEPT|DROP
    Set the policy for the chain

RULE SPECIFICATION PARAMETERS
  -s, --source address[/mask][,...]
  -d, --destination address[/mask][,...]
    Specifies IP address. Host names authorized.

  -i, --in-interface name
  -o, --out-interface
    Self-explanatory

  -p, --protocol protocol
    tcp, udp, udplite, icmp...
    "!" before the protocol inverts the test
    more parameters can be specified inside, eg:
    -p udp --sport 80
    -p tcp --dport ssh
    -p icmp --icmp-type echo-reply

  -m, --match match
    specifies a match module, further options are then used, mainly:
    -m connbytes --connbytes from[:to] min
    -m connbytes --connbytes from[:to] !max
    -m connbytes --connbytes-mod {packets|bytes|avgpkt}
    -m state --state {NEW|ESTABLISHED|RELATED|INVALID}

  -j, --jump target
    Specifies what to do with the matched packet

CONNECTION TRACKING
  uses the -m option
```

Useful examples:

Cleanup all the existing rules:

```
iptables -F
```
Set default policy to the FORWARD table:

```
iptables -P FORWARD DROP
```
Block the traffic from IP=1.2.3.4:

```
iptables -A INPUT -s 1.2.3.4 -j DROP
```
Allow HTTP traffic on interface eth0:

```
iptables -A INPUT  -i eth0 -p tcp --dport 80 -m state --state NEW,ESTABLISHED -j ACCEPT
iptables -A OUTPUT -o eth0 -p tcp --sport 80 -m state --state ESTABLISHED     -j ACCEPT
```
Allow ping from outside:

```
iptables -A INPUT  -p icmp --icmp-type echo-request -j ACCEPT
iptables -A OUTPUT -p icmp --icmp-type echo-reply   -j ACCEPT
```