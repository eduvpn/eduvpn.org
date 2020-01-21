---
title: Uninstall the macOS app
published: 2020-01-19
hide: yes
---

When upgrading from the eduVPN for macOS version `dmg` installation to the 
version from the macOS App Store, a residual "helper service" and some 
application data that will never be used anymore are left on the system. 

Run the following commands in a terminal to fully remove the service:

    $ sudo launchctl remove org.eduvpn.app.openvpnhelper
    $ sudo rm -f /Library/LaunchDaemons/org.eduvpn.app.openvpnhelper.plist
    $ sudo rm -f /Library/PrivilegedHelperTools/org.eduvpn.app.openvpnhelper
    $ defaults delete org.eduvpn.app SUHasLauchedBefore

And the application data:

    $ rm -rf ~/Library/Application\ Support/eduVPN/
    $ rm -rf ~/Library/Caches/org.eduvpn.app/
    $ rm -f ~/Library/Preferences/org.eduvpn.app.plist

You can also run the uninstall script found 
[here](https://raw.githubusercontent.com/eduvpn/macos/master/uninstall.sh).
