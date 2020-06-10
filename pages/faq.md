---
title: FAQ
hide-page: yes
---

### Can we have an IPv4 only VPN?

eduVPN is a full dual-stack IPv6 solution. It was built from the start with 
support for IPv4 and IPv6. Even if IPv6 is not used "upstream", the client will
still get assigned an IPv6 address as well as an IPv4 address. IPv6 support was
a hard requirement for some organizations that were involved in the initial 
development of eduVPN, and thus we never even made this "optional". 

We believe that supporting both IPv4 and IPv6 equally is something every (VPN) 
service should have done already for a very long time and avoids having to 
deal with "leaking" IPv6 traffic outside of the VPN tunnel in some scenarios.

That being said, if you deploy eduVPN at your organization, and IPv6 is not 
available there, you can always assign 
[ULA](https://en.wikipedia.org/wiki/Unique_local_address) addresses to the 
clients and simply drop all IPv6 traffic on your VPN server using its built-in
firewall.
