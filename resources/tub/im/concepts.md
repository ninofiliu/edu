# Alexa
Marketing company by Amazon that publishes data about top sites. *Alexa's top xxx sites* is often used as a research reference.

# ICANN
Internet Corporation for Assigned Names and Numbers
Since 1998
Manages TLDs

# IANA
Internet Assigned Numbers Authority
Since 1988
It is a role, not an organization, and this role is assumed by ICANN.
Manages the DNS root, IP addressing, AS allocation, protocol numbering (w/ IETF), and time zones standard. Distributes IPs and ASNs to the five RIRs.

# RIR
Regional Internet Registry
Manages IPs and ASNs to a local region of the world.
Five RIRs: AFRINIC (Africa), ARIN (North America), APNIC (Asia and oceania), LACNIC (Latin America), RIPE NCC (Europe and Middle East).
Each of them has slightly different policies.

# LIR
Local Internet Registry
Manages IPs to customers (typically ISPs), end users, or sub-LIRs.

# Internet layers
| OSI   | common name | links two... | data unit | protocols    |
| ----- | ----------- | ------------ | --------- | ------------ |
| 5,6,7 | application | applications | data      | HTTP         |
| 4     | transport   | hosts        | datagram  | TCP UDP QUIC |
| 3     | internet    | hosts        | packets   | IP ICMP      |
| 1,2   | link        | nodes        | frame     | IEEE802      |

# Flow
Tuple (IP src, IP dst, port src, port dst, protocol)
Note that there exist different flow definitions depending on the use case, some including the timestamp or other level 3+ header field.
(Counting) Bloom filters are used to measure them so as to locate heavy heaters.
Sample before analysis

# POP
Point of Presence
Physical point provided by an ISP where users can connect to the internet.
Typically located next to IXPs.

# IXP
Internet Exchange Point
Layer 2 peering between ASes, basically a big switch
Can also provide PNIs: private peering interconnect
