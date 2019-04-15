---
title: eduVPN 2.0 Server
published: 2019-04-01
---

Today eduVPN 2.0 server has been released. This new server version has been 
engineered to be more robust. Based on experiences in the field, like how we 
managed group-membership, we have decided to change a few components. The 
current eduVPN client [apps](../apps.html) (Windows, macOS, Android, iOS) are 
fully compatible with the new server.

Two factor (2FA) authentication has been moved from the VPN-layer to the 
authentication layer, e.g. SAML, LDAP. 2FA will be part of the authentication 
flow, so whenever a new device wants to get access to the VPN, the second 
factor must be provided. Furthermore it is possible to configure an "expiry", 
which means the user will need to authenticate, and thus provide their 2FA 
periodically. The main reason to do this is to no longer depend on the VPN 
technology itself to provide support for 2FA and to have the ability to use 
other 2FA mechanisms, like 3rd party 2FA services and U2F/FIDO2/WebAuthn in the 
future.

eduVPN supports user-groups. Basically you can configure a VLAN/IP range and 
specific VPN session time per user group. For example a group for sysadmins 
with network access to consoles of servers, and a normal user group with 
limited access. Users can join multiple groups. Per group a profile will be 
shown in the eduVPN client. With the eduVPN 1.x server we were using VOOT and 
LDAP for group membership. The VOOT protocol allowed us to dynamically query 
which groups an user has. So the eduVPN server had direct awareness if an user 
was added or removed to a certain group. Unfortunately we found out VOOT 
doesn't and didn't gain enough traction in the international NREN community. 
To convey authorization information for access to VPN profiles, we decided to 
use LDAP / SAML attributes. For example the `memberOf`, `eduPersonEntitlement` 
or `eduPersonAffiliation` attributes. This means eduVPN server only retrieves 
group membership information after IdP login. Since we moved 2FA to the 
browser, the 2FA trigger is a re-login too and will refresh group membership 
information.

Click [here](https://list.surfnet.nl/pipermail/eduvpn-deploy/2019-January/000136.html) for more details.
