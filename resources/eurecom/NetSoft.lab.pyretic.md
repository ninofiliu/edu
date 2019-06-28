# NetSoft - Lab on pyretic

```
By Nino Filiu
Prof Adlen Ksentini
Deadline: unknown
```

## Pyretic usage

#### Launch a pyretic hub, a mininet network, pingall, and comment

The pingall returns a `0% dropped (6/6 received`. The pyretic terminal indicates that the packets exchanged were of type `packet-in`, as several of similar lines get printed during the pingall:

```
INFO:pyretic.core.runtime.Runtime:<timestamp>: Packet-in # <number>
```

These packets might be received by the controller because the hub application make the switch have no rule for matching and the default action in this case is to send the packet to the controller via a `packet-in`.

Let's check on the tables of the switch:

```
mininet > sh ovs-ofctl dump-flows s1
cookie=0x0, duration=16.998s, table=0, n_packets=24, n_bytes=1952, idle_age=2 actions=CONTROLLER:65535
```

The switch has got one rule which:
* matches all packets (`cookie=0x0`)
* sends them to the controller, with a limit of 65535 bytes/packet (`actions=CONTROLLER:65535`)

When re-querying the command a few minutes later, neither the cookie not the action fields have changed, but the duration, n_packets, and idle_age have augmented: the switch did not forgot the rule.

#### Launch a pyretic learning switch, a mininet network, pingall, and comment

I stop pyretic and mininet, purge mininet, launch the pyretic learning switch application, launch mininet with the previous configuration, and pingall.

When dumping the flows, the same rule as before appears:

```
mininet > sh ovs-ofctl dump-flows s1
cookie=0x0, duration=18.365s, table=0, n_packets=73, n_bytes=1952, idle_age=24 actions=CONTROLLER:65535
```

This have the same behavior as before: sends all packets to the controller. This is confirmed by the observation of the pyretic terminal, where a lot of packet-ins are throwns during the pingall. However, when listening to the traffic in h2 and doing a ping h1 -> h3, h2 doesn't receive anything; this shows that the switch+controller behaves like a switch which has learnt, but it's not because of the switch (whose only rule is to redirect traffic to the controller.

The logical conclusion that can be drawn from these observations is that unlike POX, whose controller tells the switch how to handle packets, Pyretic works by redirecting all the traffic to the controller where it will be handled.



## Static routing

#### What is the objective of route1 policy?

Route 1 is defined by the following:

```
route1=(
  (match(switch=1)>>fwd(1))
  +
  (match(switch=2)>>fwd(2))
  +
  ((match(switch=3)>>fwd(3)))
)
```

Which, given the port configurations, translates to:

| if the packet is in ... | he must be sent to ... |
| --- | --- |
| s1 | s2 |
| s2 | s3 |
| s3 | h6 |

#### What is the objective of myroute policy? Which type of policy it is using?

The `myroute` policy:

* matches a packet based on its source IP address
* assigns a route to this packet based on its source IP

This is a static policy: for a given source and destination IP addresses, whatever the situation, only one route can be taken by the packet.



## Firewall

#### Run the firewall and observe its behavior.

The firewall acts as predicted, blocking communication between each pair of MAC addresses precised in the .csv file, example:

```
**firewall-policies.csv**
id,mac_0,mac_1
1,00:00:00:00:00:01,00:00:00:00:00:02
2,00:00:00:00:00:03,00:00:00:00:00:02

**mininet > pingall**
h1 -> X h3
h2 -> X X
h3 -> h1 X
```

#### what is the objective of the line `return allowed >> mac_learner()`?

This is the return of main, so the whole firewall policy is there. Note that, unlike with POX, in order to have a both firewall and a learning switch application, we only run the firewall, and not both of them. This supposes that the firewall must somehow acts like a learning switch *too*.

This can be observed here. In the `allowed` variable, the whole expression of the firewall policy is contained. This filter is then piped into to the learning switch. This way, we:

1. Filter the traffic according to the firewall policy
2. Apply the learning switch algorithm to the filtered traffic

#### Why we need to use the allowed policy instead of the not allowed one?

Intuitively, a firewall blocks certain traffic, so it would be more logic to return an object expressing the traffic to block, right? Actually, it's more tricky. We must return a *pyretic policy*, which is a function that takes a packet as input and returns a set of packets. For packets that don't match the set of inputs of a pyretic policy, the default action for this policy is to drop the packet.

We also have to be able to pipe the firewall into the learning switch, mainly because both application must remain independant, but also to reuse existing code.

For these two conditions to work together, it is easier to pipe the filtered out traffic into the learning switch rather than expressing the blocked traffic and add some processing to make it work with the learning switch.
