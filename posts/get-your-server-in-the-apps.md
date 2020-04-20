---
title: Get your VPN Server in the Apps
published: 2019-10-08
modified: 2020-02-26
---

To make eduVPN easier to use, and allow users to select the VPN server they 
want to connect to, it makes sense to have them registered to be listed in the
official eduVPN apps. This post is meant for institutes or NRENs setting up 
their own VPN server, not for end-users of eduVPN.

**NOTE**: even without being included in the official eduVPN apps you can test
your server using the eduVPN apps! You can simply provide the hostname of your 
VPN server through "Add other address" or similar options in the app(s).

In order to optimize the process for getting your eduVPN server listed in 
the official eduVPN [apps](../apps.html) we outline the procedure here. Mostly 
as a public place we can refer to. In addition to our 
[policy](../download/eduVPN_Compliance_Statement_1.0.pdf) document, and as 
preparation for our 2.0 policy document, this post will focus more on the 
concrete steps one has to take to be added to the apps. This is NOT a complete
list of requirements, for that see the policy document.

### Types 

There are _two_ types of eduVPN servers:

* _Institute Access_: for institutes and NRENs, exclusive access for members of 
  that institute to the institute's network;
* _Secure Internet_: for NRENs, access by all users from all research/education 
  institutes belonging to participating NRENs, to allow for safer use of the 
  Internet).

Depending on which type of server you are tying to setup, there will be 
somewhat different requirements.

### Institute Access for Institutions

These are meant for institutes that allow exclusive access for their members to 
connect to the VPN to obtain access to the institute's (internal) network. This
type of VPN server typically replaces a "VPN concentrator" on premises at the 
institute and allows exclusive access for their researchers, students and 
staff to the internal network.

The institute  notifies their NREN about the intent to use eduVPN for their 
institute network. When the NREN is not (yet) able to cooperate/support eduVPN 
they can forward your request to us on the address listed below so we will 
support you directly. Please don't forgot: every organization should sign and 
agree with the policy document!

The following information needs to be provided in order to be added:

* A technical contact email address to be contacted in case of technical 
  problems (preferably a role-based mail address);
* End-user support contact(s), e.g. mail, URL, phone number;
* The full hostname (FQDN) of your VPN server;
* Make sure TLS is configured properly! Use e.g. 
  [SSL Server Test](https://www.ssllabs.com/ssltest/);
* The "display name" of the organization (English name, optionally other 
  languages, UTF-8 encoded, **NOTE**: do not make it too long as it may not fit
  in the UI on some (mobile) devices...);
* A 350x150px logo (PNG format);
* A signed copy of the 
  [policy](../download/eduVPN_Compliance_Statement_1.0.pdf) document by a 
  person authorized to do so at your organization;
* Send your request to 
  [eduvpn-support@lists.geant.org](mailto:eduvpn-support@lists.geant.org).

Use "Add [${FQDN}] to eduVPN apps" as title.

### Secure Internet for NRENs

The primary purpose of "Secure Internet" VPN servers is to be able protect 
users on insecure WiFi. For this use case there is no primary need to connect 
to a "home" i.e. "Institute Access" VPN server. We expect that NRENs, who offer 
eduVPN, will run at least one country/region "Secure Internet" VPN server.

Providing this type of VPN is typically restricted to NRENs to offer to their 
member institutes, or by a member institute when delegated to that member 
institute. 

These servers will allow all (international) users of eduVPN for which their 
NREN also participate in eduVPN to use the server. Only NRENs that allow such 
guest access by other participating NRENs can be listed in the applications.

The following information needs to be provided in order to be added:

* A *generic* contact email address to be contacted for general inquiries, to 
  be listed on the [Contact](../contact.html#server-operators) page;
* An *abuse* contact email address to be contacted in case of abuse (preferably 
  a role-based mail address);
* A *technical* contact email address to be contacted in case of technical 
  problems (preferably a role-based mail address);
* End-user support contact(s), e.g. mail, URL, phone number;
* A web site we can refer end-users to for this particular _Secure Internet_ 
  server (Optional);
* The full hostname (FQDN) of your VPN server;
* Make sure TLS is configured properly! Use e.g. 
  [SSL Server Test](https://www.ssllabs.com/ssltest/);
* The country / region your VPN server is located in (English name, optionally 
  other languages, UTF-8 encoded);
* A 350x150px logo/flag (PNG format);
* Full information on any filtering/blocking of traffic by your VPN server or 
  upstream network;
* The public key of your server (`sudo vpn-user-portal-show-oauth-key`), make
  sure to enable 
  [guest access](https://github.com/eduvpn/documentation/blob/v2/GUEST_USAGE.md)!;
* A signed copy of the 
  [policy](../download/eduVPN_Compliance_Statement_1.0.pdf) document by a 
  person authorized to do so at your organization;
* Send your request to 
  [eduvpn-support@lists.geant.org](mailto:eduvpn-support@lists.geant.org).

Use "Add [${FQDN}] to eduVPN apps" as title.
