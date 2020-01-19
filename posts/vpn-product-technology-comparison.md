---
title: VPN Product and Technology Comparison
published: 2019-11-20
---

## Introduction

The eduVPN service is positioned as _the_ VPN service for the international 
research and education community. We were inspired by 
[eduroam](https://eduroam.org/) to offer a secure and privacy enhancing VPN 
solution to as many researchers and students as possible. We aim to have 
endpoints in as many locations as we have collaborating National Research and 
Education Networks (NRENs). In addition, the eduVPN software is 
capable of replacing existing VPN solutions allowing access to the 
institute network. This can be done, either by self hosting the eduVPN software 
on-premises, or as a hosted solution offering provided by the NREN.

The eduVPN service is a collaboration of various NRENs, governed by 
[GÉANT](https://www.geant.org/).

As no solution, neither open source, nor proprietary existed which offered the
functionality required, we decided to build our own service as a free and open 
source project.

eduVPN integrates smoothly with existing identity management systems (IdMs) 
currently in use at many (larger) organizations. We created native VPN 
applications for the most common devices, i.e. Android, iOS, macOS, Windows 
and Linux, as that would make it easy as possible to use eduVPN.

The eduVPN server uses the community edition of OpenVPN. OpenVPN is one of 
the most widely used open source VPN solutions.

Occasionally we get the question which features eduVPN offers over 
"competitors" and why we chose OpenVPN instead of any of the other available 
open source VPN software protocols and implementations. In this post we'll dive 
into this and explain the unique aspects of OpenVPN and eduVPN and why we have 
built eduVPN this way.

## Why eduVPN?

Before diving into details regarding the various VPN protocols, we'll first 
describe the features of the eduVPN software itself:

- Integrates with common IdMs in use at many organizations, e.g. SAML, 
  Shibboleth, LDAP, RADIUS and ADFS;
- Can be configured to:
  - Provide a secure VPN service to users accessing the Internet from 
    potentially insecure locations, e.g. pubic WiFi in a coffee shop or train 
    station;
  - Serve as a VPN gateway to allow users to access the organization network 
    from a remote location, e.g. working from home;
- Simple, but powerful permission management;
- Easy to install on your own (virtual) server(s);
- No need to pay any software license fees, no matter how many users are 
  connected simultaneously;
- Native applications are available for all major platforms, i.e. Windows, 
  macOS, iOS and Android;
- Supports large scale deployments supporting many concurrent users;
- Completely free and open source software, both the server and all the 
  clients;
- Based on the reliable OpenVPN Community edition that works well in many 
  situations and on many different devices.

## VPN Technology

There were a number of competing open source VPN technologies available when we
started the project in October 2014. The most popular one was definitely 
OpenVPN. It is (relatively) easy to configure and runs everywhere. However, we 
did consider a number of other protocols and implementations. We'll describe 
each of them in more detail. Note that some of them were not yet available 
around the time the eduVPN project started.

When evaluating different VPN technologies, we considered the following 
criteria:

- Does it work over TCP? This is important to work around broken network setups 
  where UDP is blocked. Quite a few locations block all traffic except HTTP and 
  HTTPS;
- Is it available "out of the box" on Linux (server) distributions? We prefer 
  to use distribution provided kernels and packages without making 
  modifications ourselves, increasing reliability and reducing support costs, 
  especially when using an enterprise OS "LTS" edition;
- Is a VPN client library or software available on the most common 
  platforms? Does it work on Windows, macOS, iOS, Android and Linux?
- Is the VPN protocol / software stable?
- Is it secure, or easy to make secure?
- How is the performance? Is it adequate for most common use cases, i.e. browse 
  the web and access protected (web) resources on private networks?
- Can we easily debug problems when the VPN does not work?
- Is it possible to use devices that we do not officially support, for example 
  your home router running OpenWRT?
- Were (security) audits performed on the software and the protocol?
- Is the software available as open source?
- Is the VPN protocol an open standard?

### PPTP

Point-to-Point Tunneling Protocol (PPTP) used to be a very popular VPN product. 
Various Windows, Linux and BSDs support both PPTP server and PPTP client mode.

Ever since it was discovered that the Windows PPTP implementation, which was 
the most popular, was found to be insecure beyond repair it no longer
[advised](https://www.schneier.com/academic/pptp/faq.html) to use PPTP.

### IPsec

IPsec is very well integrated in most commonly used devices, both on desktop / 
laptop and mobile. It is quite difficult to create an IPsec setup that is both 
usable, from all the commonly used platforms, and secure. However, it *is* 
possible as shown more recently by the 
[Algo](https://github.com/trailofbits/algo) project.

The main drawback of IPsec (and thus Algo) is that it requires a working UDP 
connection.

### OpenConnect

[OpenConnect](https://www.infradead.org/openconnect) was originally written as 
an open source replacement for Cisco's 
[AnyConnect SSL VPN](https://en.wikipedia.org/wiki/AnyConnect) client. 
Later, support for other commercial VPN products like Pulse Secure and Palo Alto 
Networks were added as well. There is also an OpenConnect server available.

The protocol uses both UDP and TCP (TLS), but can fallback to TCP only in case
UDP does not work. It therefore works well in network that block UDP. Recently, 
the OpenConnect protocol has been written down in an, by now expired, 
[IETF draft](https://tools.ietf.org/html/draft-mavrogiannopoulos-openconnect-02) 
document.

There were three drawbacks to using OpenConnect: first, we couldn't find any 
documentation or other proof that the software received a third party audit. 
Second, the OpenConnect project didn't have "ready to use" clients available 
for the major platforms we wanted to support. Third, this opensource project 
mimicks three proprietary VPN products and therefore the future roadmap is 
highly controlled by closed source products.

### Streisand

[Streisand](http://https://github.com/StreisandEffect/streisand) is a means to 
easily create a VPN provider at a cloud provider. Streisand supports a variety 
VPN products / protocols, e.g. WireGuard, OpenConnect, OpenSSH, OpenVPN, 
Shadowsocks, sslh, Stunnel. Streisand is never meant as a solution to deploy 
and manage large scale VPN services, but offer an easy way to setup a VPN 
server that can be reached when dealing with a whole range of different network 
restrictions between your current location and the VPN server.

### WireGuard

[WireGuard](https://www.wireguard.com) is a new kid on the block. It has a very 
simple approach, uses state-of-the-art cryptography, high performance (multi 
threading) and is much more convenient to audit because of its small codebase 
compared to e.g. IPsec and OpenVPN. WireGuard is a very promising product, 
however, as written on the WireGuard webpage "WireGuard is not yet complete. 
_You should not rely on this code._". Therefore we are actively following the 
WireGuard development and supporting it. Currently, WireGuard only works over 
UDP. WireGuard itself is not a "managed solution", but we expect to integrate 
it in a future version of eduVPN.

### OpenVPN

The open source community edition of OpenVPN has a big 
[community](https://sourceforge.net/p/openvpn/mailman/). Having a big community 
increases the chances the project will live a long life. In addition, it 
assures there is a lot of documentation available, also regarding integration 
in a variety of different systems and platforms.

Furthermore, OpenVPN received extensive independent audits over the years. A 
recent report is found 
[here](https://openvpn.net/security-advisory/security-audit-vulnerabilities-resolved/).

OpenVPN has the built-in capability to tunnel over TCP, which, as mentioned 
before, is important to work in environments where UDP traffic is blocked on 
otherwise unreliable. OpenVPN had working clients available for all platforms 
we wanted to support, and more. 

## Conclusion

As no VPN products existed that offered features required by eduVPN we decided 
to build those features on top of an existing VPN technology. We believe VPN 
software should be released as open source software, which has been audited by 
third parties. Products already undergone these audits had an advantage in our 
evaluation. In addition, a product that has an extensive community including 
support for most common devices like smartphones, laptops and desktops is a big 
plus. Being able to operate in broken network setups, e.g. networks where UDP 
is broken or block is a must.

One VPN technology scored best in our evaluation: the community edition of 
OpenVPN. Therefore we decided to base eduVPN on top of OpenVPN.
