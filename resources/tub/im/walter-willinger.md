# The public internet: a network of last resort?

*Notes on Walter Willinger's lecture*

- 15PB/day traffic
- 400 AS members, 50K peerings (60%)
- studied with proprietary IXP data, revealing way more than public BGP and traceroutes

Note that this is not the end of the world to use proprietary data (you can use it only to discover, then use public data to verify).

An interesting question would be to guess what is the proportion of private VS public traffic, and the IXP itself doesn't have this information, since it only knows what happens in its own switching fabric. We can however rely on research provided by the users of these private peering themselves: for example, Facebook published that 60-90% of such traffic was private.

Akamai published more infos:
- 200K servers
- organized in 3300 server clusters
- 1700 networks, 130 countries

| type | setting | akamai routers | peerings |
| ---- | ------- | -------------- | -------- |
| 1    | eyeball | no             | implicit |
| 2    | transit | no             | implicit |
| 3    | IXP     | yes            | explicit |
| 4    | PNI     | yes            | explicit |

Akamai has a private ICN backbone. It ressembles Google and Facebook in this matter. A large amount of private traffic never goes to the public internet. A small number of PNIs carry most of the traffic. This allows large content providers to deploy their in-house protocols like QUIC.

Most cloud providers also have a pivate global backbone. Their networks are pushed very closed to the end users so as to enhance performance, security, and reliability. Traffic from datacenters and servers stays on private networks. Cloud providers often propose customers to directly connect with the private network (Amazon's ExpressRoute, Google's Dedicated Interconnect).

Colocation providers, such as Equinix, are starting to compete with global ISPs.