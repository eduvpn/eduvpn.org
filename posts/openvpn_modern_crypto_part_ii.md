---
title: OpenVPN and Modern Crypto (Part II)
published: 2020-09-11
---

Last year we decided to [investigate](openvpn_modern_crypto.html) the OpenVPN 
client support of TLSv1.3 and EdDSA (Ed25519). One reason for doing this is, to 
stay 
[current](https://latacora.micro.blog/2018/04/03/cryptographic-right-answers.html) 
with algorithm recommendations by experts and 
[move away from RSA](https://blog.trailofbits.com/2019/07/08/fuck-rsa/). As 
EdDSA is easier to implement [securely](https://safecurves.cr.yp.to/) and has 
built-in protections against attacks that other curves, most notably, the NIST 
curves, do not have, there are fewer things that can go wrong. This can make 
the VPN more secure.

The other reason is performance. Generating RSA keys is slow, very slow. As we 
currently generate the keys on the server, this potentially results in high CPU 
load when many clients want to obtain a (new) certificate at around the same 
time, for example at the start of the work day. For a service with hundreds or 
thousands of users, this can create problems. Also, on a Raspberry Pi, yes 
eduVPN / Let's Connect! 
[supports](https://github.com/eduvpn/documentation/blob/v2/RASPBERRY_PI.md) the 
Raspberry Pi, it is slow to generate RSA keys, which can take many seconds. No
fun!

A simple 
[benchmark](https://github.com/letsconnectvpn/vpn-ca/blob/main/benchmark.sh) 
running on a laptop from 2012, and Raspberry Pi 3 Model B+ shows the clear 
difference. The benchmark generates a self signed CA then generates 50 keys and 
signs each of them using the CA. The time varies per execution, but they show a 
clear, very big difference. The time between brackets is key generation and
signing per certificate, on average.

Key Type | Laptop      | Raspberry Pi
-------- | ----------- | ------------
RSA      | 63s (1.26s) | 368s (7.36s)
ECDSA    | 1s          | 4s
EdDSA    | 0s          | 1s

We decided to check the status of the clients again to investigate whether it 
is possible to upgrade to TLSv1.3 and EdDSA in the next version of eduVPN / 
Let's Connect!. Luckily, much has changed since last year and support for EdDSA 
and TLSv1.3 looks a lot better now!

The eduVPN / Let's Connect! 2.x server meanwhile supports EdDSA (and ECDSA) out 
of the box, but will keep RSA as the default. However the server can easily be
configured to use ECDSA or EdDSA.

We'll again go over the list of clients that were tested last year. The updated
results can be found in the table.

Application                                                             | Works? | Version
----------------------------------------------------------------------- | ------ | --------------------------------------
[OpenVPN Community](https://openvpn.net/community-downloads/) (Windows) | Yes    | 2.4.9 on Windows 10
[Passepartout](https://passepartoutvpn.app/)                            | Yes    | 1.12.0 (2390) on iOS
[Viscosity](https://www.sparklabs.com/viscosity/) (Windows, macOS)      | Yes    | 1.8.6 (Windows, macOS)
[Tunnelblick](https://tunnelblick.net/) (macOS)                         | Yes    | 3.8.3a (build 5521)
[OpenVPN for Android](https://github.com/schwabe/ics-openvpn)           | Yes    | 0.7.16
OpenVPN Connect (iOS)                                                   | Yes    | 3.2.0 (3253)
OpenVPN Connect (Android)                                               | Yes    | 3.2.2 (5027)
Linux                                                                   | Yes    | _Modern Distributions_

This looks good! With modern Linux distributions we mean Fedora, Debian 10, 
Ubuntu 20.04, and any other distribution or OS with OpenSSL >= 1.1.1.

It seems we should be able to update to TLSv1.3 and EdDSA in the next major 
version of eduVPN / Let's Connect!. The eduVPN / Let's Connect! apps are based 
on OpenVPN (Community) and the TunnelKit library used by Passepartout. We'll 
even keep supporting the standard OpenVPN clients!
