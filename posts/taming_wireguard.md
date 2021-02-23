---
title: Taming WireGuard in eduVPN
published: 2021-02-23
---

[WireGuard](https://www.wireguard.com/) is a new, relatively simple VPN 
technology implemented the "right way". This means, no shortcuts were taken by 
the developers. Keep it simple was the motto and they were not afraid to do 
some plumbing. Two examples of this were integrating WireGuard directly in the 
Linux kernel whilst also updating the kernel's cryptography stack. The other 
was writing [wintun](https://www.wintun.net/) to make `tun` devices perform and 
work well on Windows as a replacement for OpenVPN's TAP driver.

The "keep it simple" approach is very much appreciated. Nobody wants a bloated 
VPN solution, as many of those exist already. This leaves some work when 
wanting to integrate WireGuard in "enterprise" solutions. It is unacceptable to
require someone to download the WireGuard client and copy paste a configuration 
file before being able to connect to the office VPN. So we'll need to make this 
better.

We are trying to make WireGuard a "drop-in" replacement for OpenVPN where both 
can be supported at the same time. We'll probably keep OpenVPN around for a 
while until we can solve the lack of VPN-over-TCP support in WireGuard. This 
feature is especially helpful when trying to connect from broken networks that 
either block UDP traffic, have trouble with 
[MTU](https://en.wikipedia.org/wiki/Path_MTU_Discovery)s or block all ports 
except a few common ones. With OpenVPN we can solve all these problems by 
having the client connect over for example `tcp/443`.

Luckily, the [eduVPN](https://www.eduvpn.org) software already has two parts 
which can help a lot when integrating WireGuard:

1. Server with OAuth API to configure VPN clients;
2. Client applications for all major platforms.

Currently, only OpenVPN is available in eduVPN, but leveraging the OAuth API 
that exists between server and client, integrating WireGuard is not so 
difficult anymore. However, for WireGuard we need to implement a number of 
things that are not necessary with OpenVPN.

Two major differences between OpenVPN and WireGuard are:

1. WireGuard does not allow for a "hook" to be implemented when a VPN client 
   "connects", even the entire notion of "connecting" is not as clear-cut when 
   talking only about UDP;
2. There is no IP management, i.e. WireGuard does not control and manage the 
   pool of issued IP addresses to clients.

The former is a problem when you want to implement (fine grained) 
authorization. For example in eduVPN the concept of profiles allows issuing IPs 
from a certain IP range depending on group membership as determined in the 
identity management system of the organization.

The latter is a problem when you want to make efficient use of IP addresses, 
either because you integrate in big networks where you can't simply allocate a 
`/16` block for your VPN clients, or when using public IPv4 addresses for your 
VPN clients. Public IPv4 address space has been depleted for a while now.

Technically it is not really complicated to implement this using WireGuard, but 
it requires turning the way of configuring around. Instead of implementing a
hook to determine who has access to a "profile", in this scenario we just 
provision WireGuard with the right public keys of the allowed clients and
we'll use the eduVPN application to "claim" and "release" IP addresses before
and after connecting to the VPN. Integrating this tightly with the eduVPN 
application allows for transparent operation without the user having to do 
anything different when the underlying VPN technology is WireGuard.

Building [this](https://github.com/eduvpn/documentation/blob/v2/WIREGUARD.md) 
is exactly what [Nick Aquina](https://github.com/fantostisch) did during his 
internship at [SURF](https://surf.nl/). Modifications were made to the server, 
and the eduVPN Android application was modified to use the new WireGuard API 
calls. The proof of concept works and shows that our ideas work in practice. 
All details of the work can be found in Nick's 
[thesis](../files/eduVPN-WireGuard.pdf).

Some work 
[remains](https://github.com/eduvpn/documentation/blob/v2/WIREGUARD.md#todo), 
but it is getting closer and something we look forward to make available before
the end of the year.
