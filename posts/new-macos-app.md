---
title: New eduVPN macOS app in App Store
published: 2020-01-31
---

A new eduVPN macOS app is now available from the Apple App Store.
This new version has been designed for macOS 10.14 and 10.15 and is
based on the source code of our iOS app. The big advantage of this
client is the native integration with macOS via the Apple
NetworkExtension framework. This improves the time it takes to connect and 
disconnect substantially. In addition, now we can make sure the DNS settings 
are always set correctly on connect and restored on disconnect.

Users who are running macOS 10.14 and 10.15 are encouraged to upgrade to
this new version.

On macOS 10.14 this new client is not running dual-stack e.g. the client
only supports IPv4. It is not yet certain we will fix this in a future
release due to a limitation in a third party library.

### How to upgrade to the new app

Go to the App Store with 
[this](https://apps.apple.com/app/eduvpn-client/id1317704208) link. Install the 
new client first. If the old client is running, the App Store will close the 
old app.

Start the new app. The app will notify you the old app hasn't been fully
removed. In order to fully remove the app you can either follow the
instructions on the link offered by the app, or just run this one liner in
a command line terminal (paste complete line):

    bash <(curl -s https://raw.githubusercontent.com/eduvpn/macos/master/uninstall.sh)
