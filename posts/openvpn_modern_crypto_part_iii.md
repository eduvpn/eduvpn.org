---
title: OpenVPN and Modern Crypto (Part III)
published: 2021-02-04
modified: 2021-02-05
---

[Previously](openvpn_modern_crypto_part_ii.html), and 
[earlier](openvpn_modern_crypto.html), we looked at modernizing the TLS 
configuration of OpenVPN. However, TLS is not the only cryptography used by 
OpenVPN. There's also the data channel. This post will look into using another
data channel encryption algorithm to see whether it can be beneficial for 
OpenVPN performance and under what circumstances.

There are a number of options when choosing a cipher for the data channel. The
most obvious one, and until very recently, the best available in OpenVPN is 
`AES-256-GCM`. This algorithm has been used from the start by 
[eduVPN](https://www.eduvpn.org/).

Recently 
OpenVPN [2.5](https://github.com/OpenVPN/openvpn/blob/release/2.5/Changes.rst) 
was released which supports a modern cipher that can be used for data channel 
encryption: ChaCha20-Poly1305 as described in 
[RFC 7539](https://www.rfc-editor.org/rfc/rfc7539.html).

Most CPUs have 
[instructions](https://en.wikipedia.org/wiki/AES_instruction_set) to speed up 
AES (GCM) and implement AES-GCM in constant time, reducing the likelihood of a 
successful side channel attacks against the cipher. However, not _all_ hardware 
has it, for example the Raspberry Pi 3B+. We'll analyze the difference in 
performance of these algorithms both on a device with AES hardware support and 
one without, the (in)famous Raspberry Pi.

There may also be other 
[reasons](https://soatok.blog/2020/05/13/why-aes-gcm-sucks/) _not_ to use AES, 
irrespective of hardware acceleration. And of course, one has to trust the CPU
to properly implement AES-GCM _and_ adequately prevent side channel attacks. 
Trusting your CPU is not a given, as was demonstrated over the last years. Not 
all CPUs are entirely flawless, it turned out, starting with the 
[Meltdown](https://meltdownattack.com/) "revelations". It may be wise not to 
trust your hardware to prevent side channel attacks and instead opt for an 
algorithm that is side channel attack resistant by design, i.e. 
ChaCha20-Poly1305.

We'll do some rudimentary performance analysis of the algorithms using the 
OpenSSL command line tool:

```
for ALG in aes-256-gcm chacha20-poly1305; do
    openssl speed \
        -evp ${ALG} \
        -bytes 1500 \
        2> /dev/null | grep "^${ALG}"
done
```

This should give us a rough indication what the difference in algorithms will 
do to the performance of OpenVPN. If you want to replicate this on your own 
hardware, you need a relatively recent version of OpenSSL, as old(er) versions 
do not have the `-bytes` option, and even older version do not even have 
ChaCha20-Poly1305 support...

### Lenovo 

Our trusty Lenovo laptop has been out of support for a while now, and thus will 
not receive any firmware/microcode updates anymore to mitigate CPU bugs. Let's
see how it performs. The OS is Fedora 33 (x86_64):

| Algorithm           | Speed (kB/s) | Speed (%) |
| ------------------- | ------------ | --------- |
| `aes-256-gcm`       | 979,299      | 100       |
| `chacha20-poly1305` | 701,373      | 71        |

The result is quite clear: you can expect more performance from AES-GCM than 
ChaCha20-Poly1305 when accelerated AES is available.

### Raspberry Pi 3B+

Our Raspberry Pi 3B+ is also happily churning away with the official Fedora 33 
(aarch64):

| Algorithm           | Speed (kB/s) | Speed (%) |
| ------------------- | ------------ | --------- |
| `aes-256-gcm`       | 25,586       | 100       |
| `chacha20-poly1305` | 159,755      | 624       |

The picture is completely different here. When hardware accelerated AES is 
*not* available, you can expect a 6 time improvement in throughput when using 
ChaCha20-Poly1305 on the Raspberry Pi. Ouch!

In order to get the best performance in all cases, it _may_ make sense to allow
the _client_ to pick the algorithm to use. For example on slower devices, 
mostly (older) mobile devices may be much better off with ChaCha20-Poly1305 
than AES-256-GCM and thus be faster and save battery. An exercise for the 
reader might be to run the OpenSSL performance tests on their mobile devices :)

_Special thanks to Jan Just Keijser for help with interpreting the OpenSSL_
_speed results and his_
_[work](https://www.nikhef.nl/~janjust/presentations/OpenVPN-NL_20171109.pdf)_
_on OpenVPN performance testing._
