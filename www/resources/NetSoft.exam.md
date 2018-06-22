#### Give a definition for SDN.

Software-defined networking (SDN) is an architecture purporting to be dynamic, manageable, cost-effective, and adaptable, seeking to be suitable for the high-bandwidth, dynamic nature of today's applications. SDN architectures decouple network control and forwarding functions, enabling network control to become directly programmable and the underlying infrastructure to be abstracted from applications and network services.

#### Difference SDN-NFV

NFV is a network architecture concept that uses the technologies of IT virtualization to virtualize entire classes of network node functions into building blocks that may connect, or chain together, to create communication services.

Network Function Virtualization (NFV) is about separating software runing network function from hardware.

Thus, NFV is not dependent on SDN: It is entirely possible to implement a virtualized network function (VNF) as a standalone entity using existing networking and orchestration paradigms, eventhough NFV could be considered one of the primary SDN use cases in service provider environments because there are inherent benefits in leveraging SDN concepts to implement and manage an NFV infrastructure.

| NFV | SDN |
| --- | --- |
| re-definition of  network equipment architecture | re-definition  of  network architecture |
| born  to  meet  Service Provider  (SP)  needs | comes from  the IT  world |

#### Why NFV can be seen as an evolution of IaaS?

Infrastructure as a service (IaaS) is a form of cloud computing that provides virtualized computing resources over the internet. IaaS is one of the three main categories of cloud computing services, alongside software as a service (SaaS) and platform as a service (PaaS).

#### Difference OpenFlow and OpenFlow v2?

v2 allows for data plane programmability -> allow the controller to tell the switch how to operate, rather than be constrained by a fixed switch design. In other words, what’s being proposed is a new type of switch, one that’s configurable in ways that aren’t possible today.

### Might be asked

#### What is Edge computing?

Edge computing is a method of optimizing cloud computing systems "by taking the control of computing applications, data, and services away from some central nodes (the "core") to the other logical extreme (the "edge") of the Internet" which makes contact with the physical world.[1] In this architecture, data comes in from the physical world via various sensors, and actions are taken to change physical state via various forms of output and actuators; by performing analytics and knowledge generation at the edge, communications bandwidth between systems under control and the central data center is reduced. Edge Computing takes advantage of proximity to the physical items of interest also exploiting relationships those items may have to each other.

Technos: FOG and MEC

Shares the virtualization and the multitenancy (a single instance of software runs on a server and serves multiple tenants) with cloud, but:

| cloud | edge |
| --- | --- |
| high latency | low latency |
| highly centralized | mobility support |
| Most cloud apps | specialised apps: medical, cars and IoT |
| small number of large datacenters | high number of small datacenters |
| high network bandwidth | low network bandwidth |

#### MEC? Use cases?

Multi-access Edge Computing (MEC), formerly Mobile Edge Computing, is a network architecture concept that enables cloud computing capabilities and an IT service environment at the edge of the cellular network[1][2] and, more in general at the edge of any network. The basic idea behind MEC is that by running applications and performing related processing tasks closer to the cellular customer, network congestion is reduced and applications perform better. MEC technology is designed to be implemented at the cellular base stations or other edge nodes, and enables flexible and rapid deployment of new applications and services for customers. Combining elements of information technology and telecommunications networking, MEC also allows cellular operators to open their radio access network (RAN) to authorized third-parties, such as application developers and content providers.

