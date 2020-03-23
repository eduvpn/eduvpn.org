---
title: Institute Access Deployment
published: 2020-03-23
---

A number of research and education institutions in Europe, Asia and Africa have 
chosen to use eduVPN as their _enterprise_ VPN solution. eduVPN has several 
advantages, in particular, it does not suffer from any license limitation.

But how is eduVPN deployed in practice by institutions in a research and 
education context?

There are three ways to deploy eduVPN as your _enterprise_ VPN that are 
described below.

#### On-premise eduVPN installation

Most organizations start by deploying a single server, which can scale quite 
well to around 1000 simultaneously connected clients assuming at least 16 CPU 
cores with [AES-NI](https://en.wikipedia.org/wiki/AES_instruction_set) and 
adequate network performance,  e.g. >= 10 Gbit interface(s). But it is also 
possible to deploy 
[extra servers](does-it-scale.html) with in order 
to allow for a higher number of concurrent users, or distribute over different 
locations.

For installation, follow the instructions 
[here](https://github.com/eduvpn/documentation/blob/v2/DEPLOY_CENTOS.md). Your 
identity federation can help you set up SAML so you can use SSO for 
authentication. You can then be added to the client apps by following 
[these](get-your-server-in-the-apps.html) steps.

#### On-premise NREN-managed eduVPN installation

If you want to run eduVPN on-premise but don't want to manage it, it can be 
managed remotely by your NREN. 

The institute provides physical hardware, or a virtual machine running CentOS 
or Red Hat Enterprise Linux 7 with adequate resources for expected use. Your 
NREN manages the rest.

#### NREN-hosted and managed eduVPN installation

In this scenario, your eduVPN server(s) will be managed and hosted by your 
NREN. A [layer 2](https://en.wikipedia.org/wiki/Data_link_layer) connection 
will then connect back to your institution. This is for example the model 
chosen by SURF in the Netherlands.

End-users will be able to use the eduVPN client apps (available for most 
operating systems) and use their federated identity to log in.

If you have any questions, please [contact](../contact.html) us!
