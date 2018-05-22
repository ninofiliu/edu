# Security Applications in Networking and Distributed Systems

* Professor: Refik Molva (refik.molva@eurecom.fr)
* Slides are on my.eurecom.fr
* Notes status: last slide noted = part 2 slide ?

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
* **Primary token**: associated to a process. Represents a its security subject. Inherited to child processes.
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
    (IP|IPSec|data) over
  [internet]
    (IP|IPSec|data)
  [H2]

network2network:
  [H1]
    /IP.H1|data/
  [intranet]
    /IP.H1|data/
  [Security gateway 1]
    (IP.SG1|IPSec|IP.H1|data)
  [internet]
    (IP.SG1|IPSec|IP.H1|data)
  [Security gateway 2]
    /IP.H1|data/
  [intranet]
    /IP.H1|data/
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

| component | provides |
| AH (authentication header) | data integrity, origin authentication, protection against replays |