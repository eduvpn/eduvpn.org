---
title: Lessons learned: Upgrade to eduVPN 2.0
published: 2019-05-21
---

As [announced](eduvpn-2-0-upgrade.html), on May 14th we started the upgrade to 
[eduVPN 2.0](eduvpn-server-2-0.html). It didn't _all_ go as smooth as hoped, 
but also not totally unexpected due to the complexity of the upgrade scenario. 
The option to perform a clean installation was only taken by the Norwegian 
server operator by setting up a server in parallel before and "migrating" to it 
on the 14th. Looking back, this was by far the best approach and should have 
been taken by all servers. The upgrades in Denmark, Ukraine and Uganda worked 
without big problems and didn't need support, except a reminder to Uganda that 
you can't reuse the old configuration files as-is and help Denmark with the new 
way administrators for the portal are configured.

## The Netherlands and Australia

Upgrading the Dutch server took longer than expected, I optimistically thought
it would take around 10 minutes, but in the end we managed to be back up within 
the hour. The delay was for the most part due to it running on two servers, one 
for the "portal" and one for the OpenVPN processes. Also 20+ minutes were 
wasted on a simple typo in one of the configuration files configuring SAML. 
The Australian server, also managed by us, was easy as it was running on single 
server.

## Germany

The German server had some more trouble. First, the `remove_1.0_debian.sh` 
script was not properly tested by us and thus failed to remove all components 
necessary which left some old packages and configuration files installed 
preventing the `deploy_debian.sh` script for 2.0 to successfully 
complete. The other problem was restoring SAML. The 
[mod_auth_mellon](https://github.com/UNINETT/mod_auth_mellon) (Mellon) 
authentication module for eduVPN changed and removed some functionality that 
turned out to be critical for the German deploy, or actually all deployments 
using "mesh" federations. Due to the wrong initial documentation on how to 
configure SAML, the "Name ID" was used to determine the unique user identifier. 
This has long been superseded by the `eduPersonTargetedID`, which in turn is 
superseded by the [Subject Identifier Attributes Profile](https://docs.oasis-open.org/security/saml-subject-id-attr/v1.0/cs01/saml-subject-id-attr-v1.0-cs01.html), 
the latter, not yet being widely supported by identity federations at the 
moment.

Due to Mellon not supporting any form of "scoping", i.e. binding and enforcing
identifiers to a particular IdP, the 1.0 release had a hack that prefixed the
user identifier and permissions with the entity ID of the IdP. This was removed
for the 2.0 release as we expected VPN servers operating in a mesh federation
to switch to [Shibboleth](https://www.shibboleth.net/products/service-provider/). 
But as the documentation for eduVPN in combination with Shibboleth is not 
finished yet, and due to the complexity of the migration to Shibboleth, this 
was not something that could be done in a few minutes.

Switching to the experimental embedded
[php-saml-sp](https://git.tuxed.net/fkooman/php-saml-sp/about/) module also 
failed. The virtual platform initially did not expose the AES-NI instruction to 
the guest, so hardware accelerated AES-256-GCM support was not available. Once 
this was resolved, it turned out the IdPs in the federation do not have a way 
to find out which algorithms are supported by an SP. As the algorithm support 
for php-saml-sp is deliberately quite limited, this was also not an option. 
Stuck between a rock and a hard place...

Back to Mellon was the only short term option and we applied a hotfix to take 
the IdP of the user in consideration again to make sure there could not be any 
collisions or user impersonation. Unfortunately that still didn't 
work, because we ran into a Mellon problem on Debian where the `AuthnRequest` 
was signed incorrectly and thus the messages got rejected by the IdPs. Turning
off signed `AuthnRequests` solved that problem. As it is currently not 
possible to switch the user identifiers to `eduPersonTargetedID` the hotfix 
was extends to also support the "Name ID" again. Luckily the service is a 
pilot in Germany 😀

## Conclusion

Don't remove functionality that installed servers depend on without having a 
properly tested alternative. Make upgrades easier to perform. 

The big problem with upgrading from 1.0 to 2.0 was that so many things changed
that it was almost impossible to automate this. As the number of server was so 
small, we decided not to invest in this. For the upgrade to 3.0 we try to take
a different approach: no configuration format changes during the life of 2.0. 

This will still be difficult when using e.g. SAML, where there are also 
external components like Mellon and Shibboleth involved where we 
can't automatically update the configuration. So, authentication backends will
remain a weak point. This is also one of the reasons we started developing 
php-saml-sp, as a "embedded" SAML that won't have this problem and can perform
reliable upgrades. Hopefully all mesh federations can implement support for 
announcing supported encryption algorithms to their IdPs in time for the 3.0 
upgrade...
