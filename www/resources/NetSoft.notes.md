# Network softwerization

| Lecture | Completion status |
| --- | --- |
| SDN | to do |
| NFV | to do |
| Mobile edge computing | to do |

## SDN

### Why and what?

Software defined networking is a new trend in computer networks based on the concept that slightly more intelligent switches (**forwarding devices**) communicate with a **controller** in order to know how to route the packets. That break the OSI layered model but allows for:

* logical centralization
* dynamism
* greater control
* easier control

In a nutshell, networks can evolve at the speed of software.

### Plans

In an operating system, the OS make applications and hardware communicate. In a network operating system, the NOS make NApps and forwarding devices communicate:

```
OS:
  app1, app2,  app3
  (commands)
  OS
  (ISA)
  screen, CPU, network card

Network OS:
  Napp1, Napp2, Napp3   "management plan"
  (northbound API)      |
  NOS aka controller    | "control plan"
  (southbound API)      |
  forwarding devices    "data plan"
```

And just like several apps can run on a computer for a particular user experience, several apps can run on the networks so as to perform a variety of tasks and optimizations (firewall, load balancer, etc).

The **data plan** is the fast component of a router where dumb routing decisions are undertaken in nanoseconds based on tables. The **control plan** is the component that gives instructions to the data plan in a matter of microseconds, and in order to do that, the topology of the network must be known. SDN allows for an abstraction of both of these components thanks to the north and south APIs.

### Southbound API

#### OpenFlow

[https://en.wikipedia.org/wiki/OpenFlow](https://en.wikipedia.org/wiki/OpenFlow) is the most  widely  accepted  and deployed  open  southbound  standard  for SDN, but others exist. OpenFlow is at TCP:6653/TLS. All in all, OpenFlow does layer 3 table modification on switches, but as a concept, it is more complex.

Switches have match/action **tables**: if a match is found in one of the **table entry**, the corresponding action set is undertaken (drop packet, modify packet fields, etc). If there is no match at the end of the table, the default table action is undertaken (often go to next table, drop, or send to controller).

More precisely, a table entry have these fields:

* match field: based on layer 2,3,4 fields (port, eth, IP, ...)
* priority: for entry selection among different entries that all match
* counters: counts matches
* idle timeout: removes entry after a certain time if no match
* hard timeout: removes the entry after a certain time no matter what happened
* instructions: update packet, update action set, or update metadata

An action can be:

* ALL
* FLOOD
* CONTROLLER: encapsulate the packet in a **PACKET_IN** and send it to the controller
* ...
* Action options:
  * set header field

Note the absence of DROP: that is because no action = DROP.

Controllers -> switch communications can be in the form of a:

* **FLOW_MOD**: modify the switch state to install rules
* **PACKET_OUT**: (in response to a PACKET_IN) to specify what to do with the packet_in-ed packet.

#### Other APIs

**OpFlex**: controller tells the policy to the network device, which implements it in its own way.

**NetConf**: yang language to build something like an SNMP on steroids

