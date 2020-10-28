---
title: Join
---

To make eduVPN easier to use for your users, you can have your server 
registered to be listed in the official eduVPN [apps](apps.html). This page is
meant for institutes and/or NRENs setting up their own VPN server, not for 
end-users of eduVPN.

**NOTE**: even without being included in the official eduVPN apps you can test
your server using the eduVPN apps! You can simply provide the hostname of your 
VPN server through "Add other address" or similar options in the app(s).

**NOTE**: please make sure your server is fully up to date and working properly 
before requesting to be added to the applications.

This page shows the information we need to add your server. This is a more 
concrete version of the 
[policy](download/eduVPN_Compliance_Statement_1.0.pdf) document. Make sure you 
also read the policy document!

### Types 

There are _two_ types of eduVPN servers:

* **Institute Access**: for institutes and NRENs, exclusive access for members 
  of that institute to the institute's network;
* **Secure Internet**: for NRENs, access by all users from all 
  research/education institutes belonging to participating NRENs, to allow for 
  safer use of the Internet).

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
* End-user support contact(s), at least one of: mail, URL, phone number. 
  **NOTE** these will become public;
* The full hostname (FQDN) of your VPN server;
* Make sure TLS is configured properly! Use e.g. 
  [SSL Server Test](https://www.ssllabs.com/ssltest/);
* The "display name" of the organization (English name, optionally other 
  languages with [BCP 47](https://tools.ietf.org/html/bcp47) tags, everything 
  UTF-8 encoded, **NOTE**: do not make it too long as it may not fit in the UI 
  on some (mobile) devices...);
  * Example: `en-US`: Radboud University, `nl`: Radboud Universiteit
* A 350x150px logo (PNG format);
* A signed copy of the 
  [policy](download/eduVPN_Compliance_Statement_1.0.pdf) document by a 
  person authorized to do so at your organization;
* Send your request to 
  [eduvpn-support@lists.geant.org](mailto:eduvpn-support@lists.geant.org), use 
  "_Add [${FQDN}] to Institute Access eduVPN_" as title.

#### Template

Use the following example template in your mail to 
[eduvpn-support@lists.geant.org](mailto:eduvpn-support@lists.geant.org), please
update all values for your situation:

Subject: `Add [vpn.example.org] to Institute Access eduVPN`

Body:
```
Technical Contact: eduvpn@example.org
End-user Support Contact: 
  - support@example.org
  - +1234567890
  - https://support.example.org/
FQDN: vpn.example.org
Display Name: 
  en: My Organization
  nl: Mijn Organisatie
```

Do **NOT** forget to attach the signed copy of the policy document and the 
350x150px PNG logo!

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
  be listed on the [Contact](contact.html#server-operators) page;
* An *abuse* contact email address to be contacted in case of abuse (preferably 
  a role-based mail address);
* A *technical* contact email address to be contacted in case of technical 
  problems (preferably a role-based mail address);
* End-user support contact(s), at least one of: mail, URL, phone number.
  **NOTE** these will become public;
* A web site we can refer end-users to for this particular _Secure Internet_ 
  server (Optional);
* The full hostname (FQDN) of your VPN server;
* Make sure TLS is configured properly! Use e.g. 
  [SSL Server Test](https://www.ssllabs.com/ssltest/);
* The name of the country / region your VPN server is located in (English);
* Full information on any filtering/blocking of traffic by your VPN server or 
  upstream network(s), either because of legal reasons or local policy;
* The public key of your server (`sudo vpn-user-portal-show-oauth-key`), make
  sure to enable 
  [guest access](https://github.com/eduvpn/documentation/blob/v2/GUEST_USAGE.md)!;
* A signed copy of the 
  [policy](download/eduVPN_Compliance_Statement_1.0.pdf) document by a 
  person authorized to do so at your organization;
* Send your request to 
  [eduvpn-support@lists.geant.org](mailto:eduvpn-support@lists.geant.org), use
  "_Add [${FQDN}] to Secure Internet eduVPN_" as title.
  
#### Template

Use the following example template in your mail to 
[eduvpn-support@lists.geant.org](mailto:eduvpn-support@lists.geant.org), please
update all values for your situation:

Subject: `Add [vpn.example.org] to Secure Internet eduVPN`

Body:
```
Generic Contact: admin@example.org
Abuse Contact: abuse@example.org
Technical Contact: eduvpn@example.org
End-user Support Contact: 
  - support@example.org
  - +1234567890
  - https://support.example.org/
Information Website: https://www.example.org/services/eduvpn
FQDN: vpn.example.org
Country / Region: The Netherlands
Restrictions: 
  - in/outbound tcp/25 blocked
Public Key: O53DTgB956magGaWpVCKtdKIMYqywS3FMAC5fHXdFNg
```

Do **NOT** forget to attach the signed copy of the policy document!
