---
title: php-saml-sp: A simple method for SAML integration, audited and released!
published: 2021-01-07
---

As demonstrated several times (latest in this 
[video](https://youtu.be/yBItHovq4AU)), it only takes a few minutes to deploy 
an eduVPN server. What typically takes more time is the discussion concerning 
the user groups to define in the eduVPN server and how to integrate this (like 
firewall settings, identity management aspects, Active directory integration) 
with the IT services provided internally.

Over the years, we found out that a lot of people had difficulties integrating 
SAML with their eduVPN server. The existing methods are either complicated, 
include too many features, have hard requirements on Apache and are not easy to 
package for server operating systems like CentOS/Fedora and/or Debian. When 
choosing for SAML identity integration instead of LDAP/RADIUS a typical 
deployment only needs SAML SP support, so there is no need to include any IdP 
features, or other (obsolete) authentication protocols.

So we decided to create our own library and to implement what is actually used 
"in the field" and what is secure (you won’t find SHA1 support), The software 
is written in PHP and as everything else in eduVPN, it is released as free 
open source software. It is called php-saml-sp.

It provides a complete SAML 2.0 SP solution and can be used to connect a 
service written in PHP with a SAML IdP or identity federation. It is by no 
means specific for eduVPN and can be used by any PHP application. To put 
simply, it supports most features of SAML that are not considered insecure 
and is very easy to configure and integrate in your application. In addition, 
RPM and DEB packages are provided for easy installation on CentOS, Fedora and 
Debian.

Two unique features of php-saml-sp were required for successful integration 
with the eduVPN project:

1. Support for SAML based MFA authentication (SURFsecureID) where the "LoA"
   depends on what type of user authenticates, i.e. higher LoA for 
   administrators than for students;
2. Handle "discovery first" scenario in the new eduVPN applications when 
   working with "Hub&Spoke" identity federations

In late October and November, it was audited by [Cure53](https://cure53.de). 
You can find their report 
[here](https://git.sr.ht/~fkooman/php-saml-sp/blob/main/audit/DEC-01-report.final.pdf). 
In the summary of the report, Cure53 wrote:

> ...the impressions gained from this autumn 2020 project on the whole were 
> relatively positive. No issues beyond a Medium severity rating were 
> detected"; Furthermore, in Cure53's view, the in-house team appears to have 
> a firm grasp on current, optimum development practices. Recently all 
> vulnerabilities have been mitigated so the application should enjoy the 
> fruits of a strong security infrastructure, as alluded to by the positive 
> results detailed within this report.

To use php-saml-sp with eduVPN see 
[here](https://github.com/eduvpn/documentation/blob/v2/PHP_SAML_SP.md). For 
generic information about php-saml-sp see 
[here](https://sr.ht/~fkooman/php-saml-sp).
