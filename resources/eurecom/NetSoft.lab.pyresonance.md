# State-based network policies with Pyresonance

```
By:       Nino Filiu
Prof:     Adlen Ksentini
Deadline: unknown
```

## Pyresonance usage

By default, all communication is dropped:

```
mininet> pingall
h1 -> X X
h2 -> X X
h3 -> X X
```

But after setting the states `auth -> authenticated`and `ids -> clean}` the traffic flows normally between h1 and h2:

```
mininet> pingall
h1 -> h2 X
h2 -> h1 X
h3 -> X X
```

Diving into the code, we can deduce that the FSM is the following:

```
application=auth:
  state=authenticated:
    policy=passthrough
  state!=authenticated:
    policy=drop
application=ids:
  state=clean:
    policy=passthrough
  state=innfected:
    policy=drop
```

Packet are first authenticated and then piped into the ids app.



## Access control application

The files are changed like so:

#### global.config
```
APPLICATIONS = {
  pyretic.pyresonance.apps.auth,
  pyretic.pyresonance.apps.ids,
  pyretic.pyresonance.apps.your_app,
}
COMPOSITION = {
  auth >> ids >> your_app
}
```

#### json_sender.py
```python
# line 34
op.add_option(
  '--event-type',
  '-e',
  type='choice',
  dest="event_type",
  choices=['auth','ids','serverlb','ratelimit','your_event'],
  help='|'.join(['auth','ids','serverlb','ratelimit','your_event'])
)
```

All traffic is blocked but running the following script enables the traffic to flow normally between h1 and h2:
```bash
python ~/pyretic/pyretic/pyresonance/json_sender.py --flow='{srcip=10.0.0.1}' -e auth -s authenticated -a 127.0.0.1 -p 50001
python ~/pyretic/pyretic/pyresonance/json_sender.py --flow='{srcip=10.0.0.2}' -e auth -s authenticated -a 127.0.0.1 -p 50001
python ~/pyretic/pyretic/pyresonance/json_sender.py --flow='{srcip=10.0.0.1}' -e ids -s clean -a 127.0.0.1 -p 50002
python ~/pyretic/pyretic/pyresonance/json_sender.py --flow='{srcip=10.0.0.2}' -e ids -s clean -a 127.0.0.1 -p 50002
python ~/pyretic/pyretic/pyresonance/json_sender.py --flow='{srcip=10.0.0.1}' -e your_event -s allow -a 127.0.0.1 -p 50015
python ~/pyretic/pyretic/pyresonance/json_sender.py --flow='{srcip=10.0.0.2}' -e your_event -s allow -a 127.0.0.1 -p 50015
```



## DDoS application

The states of the FSM contain `ddos-attacker`. Other states are not specified.

After modifying `dos_policy.py`, we launch mininet, sflow and pyretic as instructed in the lab subject. Launching `pingall` shows that all hosts can communicate normally. However, after launching `h1 ping h2 -i 0.05 -c 200`, all traffic to and from h1 is blocked. That is because h1 sent too many packets in a time interval that was too short, so it was detected as an attacker.
