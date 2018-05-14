# Security Applications in Networking and Distributed Systems

* Professor: Refik Molva (refik.molva@eurecom.fr)
* Slides are on my.eurecom.fr
* Notes status: last slide noted = part 2 slide 0

## Access control (AC)

### Access control model

The model is a matrix of **rights**: `R[i][j]` is the right of the i-th **subject** over the j-th **object**. Each subject has a **capability list (CL)** and each object an **access control list (ACL)**. A **permission** is a `(object,right)` tuple defining the authorized operations on an object.

Decision and enforcement have to be taken according to this rights matrix. There are two strategies:

* **Discretionar access control (DAC)**: the AC decision is left to the owner, ex: UNIX and Active Directory
* **Mandatory access control (MAC)**: the AC is defined by system-wide mandatory rules, ex: TrustedBSD

In some systems, AC is enforced by the subjects sending its CL along with its request; this requires authentication and integrity verification. In other systems, the request is sent alone and the OS enforces the AC thanks to its ACL. There is a flexibility-control trade-off between the two options. Other systems exist, for example the user agent is the framework for Kerberos in Windows:

```
AC agent

	1. subject → agent  authorization request
	2. agent → subject  authorization
	3. subject → object access request + authorization
```

More generally, the AC framework in the ISO standards is defined by the frour components that interact in the following fashion:

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

In a nutshell, users are associated with roles which are associated with permissions. This reduces the complexity and the length of the process for creating new objects or subjects but the previous matrix model is nnot enough, rather we have two elements:

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
1. server				generate name, private key, and public key
2. server -> RA name + public key
3. RA -> CA 		name + public key + registration
4. CA -> server signed certificate
```

To validate a certificate, the process is the following:

```
1. server -> client	signed certificate
2. client -> VA 		signed certificate + validation
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