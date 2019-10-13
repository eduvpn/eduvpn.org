---
title: OpenVPN and Modern Crypto
published: 2019-10-11
---

We decided to investigate what it would take to modernize the cryptography used 
to establish the TLS control channel with OpenVPN. See our current 
[configuration](https://github.com/eduvpn/documentation/blob/v2/SECURITY.md) 
documentation as used by eduVPN. The reason for looking into this is to get 
[improved security and increased performance](https://www.ietf.org/blog/tls13/), 
when switching to TLSv1.3 and possibly switching key type. This is relevant for 
the connection setup when establishing a OpenVPN connection.

What we tried to accomplish these two tasks specifically:

1. Use TLSv1.3 instead of TLSv1.2;
2. Use Ed25519 instead of RSA for the OpenVPN client and server keys.

In order to test with TLSv1.3 and Ed25519, we used our own CA for issuing 
certificates, [vpn-ca](https://github.com/fkooman/vpn-ca) that also 
recently added [support](https://github.com/fkooman/vpn-ca/tree/ed25519) for 
Ed25519 keys and certificates and will in the near future replace 
[easy-rsa](https://github.com/OpenVPN/easy-rsa) in eduVPN.

In order to modify the configuration, we can specify that the minimum version
of TLS that can be used is 1.3.

    tls-version-min 1.3

We no longer need the `tls-cipher` configuration option, as all ciphers 
supported by TLSv1.3 are secure. The client can decide.

It turns out, not many (existing) OpenVPN clients are capable of supporting 
TLSv1.3 and/or Ed25519 keys out of the box.

Client                         | Works?    | Notes
------------------------------ | --------- | --------------------------------------
[OpenVPN Community](https://openvpn.net/community-downloads/) (Windows)    | No        | OpenSSL 1.1.0j
[TunnelKit](https://github.com/passepartoutvpn/tunnelkit) | No        | OpenSSL-Apple (1.1.0l.4) **(1)**
[Viscosity](https://www.sparklabs.com/viscosity/) (Windows, macOS)      | No        | OpenSSL 1.0.2t **(2)**
[Tunnelblick](https://tunnelblick.net/) (macOS)            | **Yes**   | Requires Config Change **(3)**
[OpenVPN for Android](https://github.com/schwabe/ics-openvpn)            | **Yes**   | has OpenSSL 1.1.1a
OpenVPN Connect (iOS)          | No        | OpenSSL 1.0.2s, mbed TLS 2.7.12
OpenVPN Connect (Android)      | No        | OpenSSL 1.0.2s, mbed TLS 2.7.12
Linux                          | **Maybe** | when distribution has OpenSSL >= 1.1.1

The eduVPN clients are based on some of the above, so whatever the above 
clients support is important for eduVPN as well!

Clients depending on OpenSSL needs to use OpenSSL >= 1.1.1, otherwise there is 
no support for TLSv1.3 and Ed25519. When a client uses mbed TLS, there is no 
support for TLSv1.3 or Ed25519 at all.

Interestingly, some clients use OpenSSL versions that are no longer 
supported upstream. Only 1.1.1 and 1.0.2 are still supported. See the OpenSSL 
[Release Strategy](https://www.openssl.org/policies/releasestrat.html).

For eduVPN specifically, there may be some additional hurdles to take: the 
platform may not support Ed25519 keys in its platform native certificate store.
More research is needed here, but having "Yes" in the "Works?" column above is
a good first step, even when we are required to use RSA keys for a little while 
longer.

### Notes

**(1)** We opened an [issue](https://github.com/passepartoutvpn/tunnelkit/issues/123) 
upstream

**(2)** We sent a mail on 2019-10-11 to `support@sparklabs.com` to ask for 
updating OpenSSL as used by Viscosity

**(3)** In Tunnelblick, the "OpenVPN version" needs to be changed to 
`2.4.7 - OpenSSL v1.1.1d` under "Configurations". We tested with 
Tunnelblick 3.8.2beta01 (build 5401)
