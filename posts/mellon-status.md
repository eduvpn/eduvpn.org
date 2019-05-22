---
title: Mellon (SAML) Support Status
published: 2019-05-22
---

Recently, during the upgrades to eduVPN 2.0, we've been having some trouble 
with the [mod_auth_mellon](https://github.com/UNINETT/mod_auth_mellon) Apache
module.

1. The Debian 
   [package](https://packages.debian.org/stretch-backports/libapache2-mod-auth-mellon) 
   generates invalid signatures over `AuthRequest` messages which wasted a lot
   of time debugging (issue reported to the Debian package maintainer)...
2. Mellon does NOT properly support `eduPersonTargetedID` (serialization)
3. Mellon does NOT check the "scope" of attributes against the IdP metadata 
   `<shibmd:Scope>` attribute.

This is not a problem for simple deploys when using a single IdP, or when using
a SAML proxy, i.e. a _hub & spoke_ federation, where there is some control and 
enforcement of the attributes received by the SP. Then Mellon is actually a 
good choice as it is relatively easy to configure.

For _mesh_ federations this does not really suffice. One has to be able to rely 
on the attribute values without needing to implement e.g. scope validation or 
`eduPersonTargetedID` validation/serialization in the application code. 

If at all possible install and use 
[Shibboleth](https://www.shibboleth.net/products/service-provider/). This is
supported by eduVPN 2.0. Setting up Shibboleth is quite complicated, this is 
out of scope of the eduVPN project. We did make documentation available 
[here](https://github.com/eduvpn/documentation/blob/v2/SHIBBOLETH_SP.md) on 
how to configure eduVPN after setting up Shibboleth. You should contact your 
identity federation for more information and instructions on how to exactly
configure Shibboleth.

We are currently working on native SAML support for eduVPN 3.0. We will see if 
this covers all the cases we require and which are currently supported by 
Shibboleth, if not, we'll retain Shibboleth support as an option. The plan is 
to drop support for Mellon in favor of the native SAML support, making SAML 
configuration a lot easier.

To sum up: if you are part of a _mesh_ federation, please do not use Mellon and
switch to Shibboleth.
