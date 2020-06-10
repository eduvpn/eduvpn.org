---
title: FAQ
hide-page: yes
---

## Client
### I found a bug in the eduVPN client, how to report?
All known bugs in the eduVPN clients will be put in Github. Please report bugs with detailed information about which OS version, client version and log-files directly in Github. Please make sure there is no personal data included, because this bug information will be public.
[Windows](https://github.com/Amebis/eduVPN/issues)
[macOS & iOS](https://github.com/eduvpn/apple/issues)
[Android](https://github.com/eduvpn/android/issues)
[Linux](https://github.com/eduvpn/python-eduvpn-client/issues)



### After install, client asks "repair" or "uninstall" (Windows)
Some users experience after installing the eduVPN Windows clients an notification "repair or uninstall" the client.
The notification indicates an eduVPN component didn't install correctly. Sometimes a Windows reboot solves the issue. In other cases we noticed Window update was not working, so the computer wasn't up to date. Trying to fix the Windows update functionality might solve the issue.

### Virusscanner reports suspicious software when installing client (Windows)
Be certain you've downloaded the eduVPN via [https://app.eduvpn.org/](https://app.eduvpn.org/) If this is the case it is probably a false positive notification. Nowadays Virusscanners use whitelisting mechanisms which mean that if we release a new version, they might alert because they didn't see the binary anywhere before. In order to avoid false positives we will always upload our new eduVPN clients to [Virustotal](https://www.virustotal.com/) Via this platform all Virusscanners will become aware of our new software releases. You can always check our binaries yourselves via VirusTotal, just upload the binary and view the report.

### eduVPN client is only available in Appstore for macOS 10.14 and higher (macOS)
In order to 'be' in the Appstore with the eduVPN client we had to implement a specific functionality (NetworkExtensions). This is only available for macOS 10.14 and later. For users with older macOS versions we advice to use [Tunnelblick](https://tunnelblick.net/). How to use? Install the Tunnelblick client, login to  your eduVPN server, go to "configurations" and create a configuration. You can download the configuration file as .ovpn and import it in Tunnelblick. You can now start/stop the VPN in Tunnelblick. **Be aware** the configuration file has an expiry time. By default 90 days. When the configuration expired you can't use it anymore in Tunnelblick and need to create and download a new one. Unfortunately you won't get any descent notification about the expiry, it just won't connect. However in the log-file of Tunnelblick is visable the certificate expired.




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
