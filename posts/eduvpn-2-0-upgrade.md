---
title: "Secure Internet" Upgrade to eduVPN 2.0
published: 2019-04-30
---

On May 14th, all participating _Secure Internet_ eduVPN servers will be 
upgraded to [2.0](eduvpn-server-2-0.html). This has to be done at the same time 
as the used OAuth tokens format changed. We chose to not create a backwards 
compatible layer as the number of servers is currently still quite limited. For 
future upgrades we won't have this luxury anymore we hope 😎.

Most servers will migrate at around 10:00 (CEST), 08:00 UTC on Tuesday May 
14th so there will be server disruption, possibly throughout the day, 
especially when using the "Guest" option where you connect to the VPN endpoint
of another country from your home institution.

All client configurations will break, as well as the "authorizations" obtained
by the eduVPN [applications](../apps.html). For users of the eduVPN applications, 
it will be a simple matter of "reauthenticating" after the server upgrade is 
complete. For users that manually downloaded the VPN configuration, e.g. on 
platforms not supported by an official eduVPN application, the connection will
fail and a new configuration will need to be obtained through the web portal
of the VPN server the user is connecting to. 

### OAuth

The eduVPN server uses OAuth tokens for communication between the eduVPN 
application and the server. It is used to keep the VPN functional when e.g. a 
setting changes at the server. This makes it easier to keep the VPN "up-to-date"
and switch to new technologies or configurations. In eduVPN 1.0 the OAuth 
token was based on [libsodium](https://libsodium.org/). For 2.0 we switched to
[JWT](https://en.wikipedia.org/wiki/JSON_Web_Token) with the 
[EdDSA](https://tools.ietf.org/html/rfc8037) algorithm. This token is a little 
bit more flexible and makes it easier to support different token formats in the 
future if/when needed.

### OpenVPN

In addition, we took the opportunity to remove some deprecated OpenVPN 
configuration options to make sure a vulnerability like 
[VORACLE](https://openvpn.net/security-advisory/the-voracle-attack-vulnerability/) 
can not occur by removing all support for compression. In addition support for
`tls-auth` was removed and we only support `tls-crypt` going forward.

### Looking Forward

We are already working on eduVPN 3.0. In order to see what is planned for this
release check out the 
[Roadmap](https://github.com/eduvpn/documentation/blob/master/ROADMAP.md).
