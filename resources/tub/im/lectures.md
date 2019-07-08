# Internet measurement

All infos on [ISIS](https://isis.tu-berlin.de/course/view.php?id=16281)

Literature:
- Internet Measurement: Infrastructure, Traffic and Applications
- RFC7020: The Internet Numbers Registry System



## Intro

The theoretical internet structure we learn in school can be quite different its version we actually observe IRL. There is a big gap between 

The internet is a critical infrastructure, and we ought to understand it to be clever about performances, privacy, and policymaking; but simple questions like can be extremely difficult to answer.

The main reasons for such difficulties are the lack of central authority, the lack of ground truth, and the speed at which the internet evolves. Let's see an example: how to get IRL IP stats?

> - Theoretically $2^32$ addresses, but allocation/usage stats can't be computed with theory only
> - IRs have public databases of which address spaces has been allocated to which networks.
> - Route collectors like RIPE RIS have archives of passive BGP collecting

# Topology

## Passive measurement with BGP

Mainly three levels of complexity of the internet:
1. AS-level
2. Router-level
3. Physical-level

Eventhough the AS-level is only logical level that can be different from its physical counterpart, it is simple and gives business insights about customer/provider/peer relationships. Dumps of BGP data are provided by RIPE and RouteViews, among others. They consist in a BGP table dump (snapshot of a router's BGP knowledge) and BGP announcements (updates to this knowledge).

If AS1 prefixes AS2 in a BGP file, it means that users can reach AS2 through AS1, so AS2 is a client of AS1. Analysis has also to pre-process the data:
- AS path can be corrupted: AS1 AS2 AS2 AS3
- Martian bogons: addresses that shouldn't appear in a routing table due to RFC specs
- Full bogons: same, but because IANA/RIRs/ISPs didn't allocate them

BGP is becoming increasingly harder to implement due to the high rate linear increase in AS numbers and their related IPs.

## Active measurement

Traceroute is based on level 3 or 4 protocols thus can discover the internet topology at the host level, but not at the physical one. You can see paths not visible in the AS network, and vice-versa.

Traceroute has several limitations, mainly due to the fact that there exist several paths to a same destination. Load balancing can be at the dst-level, flow-level, or packet-level. A different route can be taken for the request and the response.

## ISPs

It is desirable to understand ISPs internal topologies - so as to get to know best practices and assess security/resilience - but they're often a business secret and are only revealed as "marketing maps". We, however, know the basic components of ISP networks: POPs, and a backbone network that connects these POPs.

ISPs, however, release useful infos:
- BGP data: which prefixes are served
- DNS: location and roles

Traceroute-probing a whole ISP internal map can be resource-intensive, but optimizations can help:
- leverage the fact that traceroute can be launched from various vantage points
- use only traceroutes that transit through the ISP
- leverage BGP data (/10 to /100)
- use path reduction (/25 to /50)
    - ingress: routes enter an AS at the same point
    - egress: routes leave an AS at the same point
    - next-hop: routes have leave an AS using the same next-hop to another AS

More information can be inferred from metadata:
- DNS name can give clues about the location and role
- Fast links to known location -> location
- Alias resolution (two IPs, same router) with IP ID (generated from the same counter)

## IXPs

- Textbook internet: backbone >(NAPs)> ISPs > customers
- Real internet: national and private backbones >(IXPs)> ISPs > customers, and sometimes private backbones are very close to end users

IXPs are on the rise, mainly because they propose a cheaper alternative compared to the pay-for-bandwith tier 1 offers.

Some IXPs, like LINX, can provide intercontinental level-2 peerings.

When traceroute-mapping IXPs, make sure to take into account the direction of the interfaces of host:
```
---AS1.1[router]IXP1.1---IXP1.2[router]AS2.1---
--->  AS1.1 (IXP1.1) IXP1.2 (AS2.1)
<--- (AS1.1) IXP1.1 (IXP1.2) AS2.1
```

IXPs provides looking glasses that can be used to infer multilateral peering links. With such active measurements, the number of such links are usually ten times higher than when using passive BGP.

# Measuring the web

## CDNs

Today's traffic is 80% web. These websites often rely on cloud providers for hosting:
- handles slashdotting and DoS
- no single point of failure
- close to clients

CDN uses reverse proxies (ie used by the server, not the client) to proactively replicate content. It allows the CDN to chose the best server for a particular user:
- HTTP redirect: + fine grain control, - extra HTTP calls
- anycast routing: + no extra calls, - doesn't consider load
- DNS-based: + quite fine control, avoid TCP delay, - based on IP of local DNS

CDNs use a front-end server that is as close as possible to the end user so as to control as much traffic as possible between the client and the origin server.

A few large CDNs are responsible for most of the web traffic: 2016 10 ASNs = 70% of consumer traffic. CDNs account for 90% of web traffic today. CDNs can be measure in IXPs, where AS-to-AS traffic of a CDN has significant chances of passing by, or at private peering locations.

## Website complexity

HTTP is the narrow waist of the future internet.

Modern websites are growing more and more complex, with communication to and from many servers and third-party services. A growing number of actions happens "behind the curtain" when accessing a webpage: search engine indexing, ad bids, CDN stuff, analytics, tracking...

Adversarially, users have less and less patience concerning the page load time, and a badly designed website can have drastic economic conscequences on a company.

To get insights about a webpage quality, tools can be used, one of them being the Chrome Audit tab, which provides a detailed report on the website quality and its loading time. Most modern browsers also have a network tab which can give precise networks measurements.



# Topics

## BGP blackholing

Widely available mitigation option against DDoS. All incoming traffic (both legitimate and malicious) is BGP-routed to a null route (the black hole). Blackholing can be done by an upstream AS of an IXP.

On the rise on all three metrics: providers, users, prefixes.

Effective in dropping traffic early.

## IP Geolocation

Several methods:
- constraint-based: a set of host with known location are being used to triangulate other IPs
- path-based: train on known host and compare paths with other locations
- router-based: detects known routers in the traceroute

The first method doesn't lean, but the other two do.

## Modeling traffic

Poisson doesn't hold because traffic is self-similar whereas Poisson is smoothing. More precisely:
- @LAN:
    - superposition of on/off sources
    - heavy tailed, infinite variance
    - packets/time: exactly self-similar
- @WAN:
    - Sessions are arriving in a Poisson manner
    - size and number of packet is heavy tailed, with infinite variance
    - number is asymptotically self-similar
