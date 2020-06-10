---
title: FAQ
hide-page: yes
---

## Client

### After install, client asks "repair" or "uninstall" (Windows)
Some users experience after installing the eduVPN Windows clients an notification "repair or uninstall" the client.
The notification indicates an eduVPN component didn't install correctly. Sometimes a Windows reboot solves the issue. In other cases we noticed Window update was not working, so the computer wasn't up to date. Trying to fix the Windows update functionality might solve the issue.

### Virusscanner reports suspicious software when installing client" (Windows)
Be certain you've downloaded the eduVPN via [https://app.eduvpn.org/](https://app.eduvpn.org/) If this is the case it is probably a false positive notification. Nowadays Virusscanners use whitelisting mechanisms which mean that if we release a new version, they might alert because they didn't see the binary anywhere before. In order to avoid false positives we will always upload our new eduVPN clients to [Virustotal](https://www.virustotal.com/) Via this platform all Virusscanners will become aware of our new software releases. You can always check our binaries yourselves via VirusTotal, just upload the binary and view the report.




## Server

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
