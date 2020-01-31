---
title: Uninstall the macOS app
published: 2020-01-19
hide: yes
---

When upgrading from the non-App Store eduVPN for macOS installation to the 
version from the App Store, a residual "helper service" and some 
application data that will never be used anymore are left on the system. 

You can either run the script using a "one liner" from the Terminal:

    bash < (curl -s https://raw.githubusercontent.com/eduvpn/macos/master/uninstall.sh)

Or, run the following commands yourself if you'd like to know what is going on:

    $ sudo launchctl remove org.eduvpn.app.openvpnhelper
    $ sudo rm -f /Library/LaunchDaemons/org.eduvpn.app.openvpnhelper.plist
    $ sudo rm -f /Library/PrivilegedHelperTools/org.eduvpn.app.openvpnhelper
    $ defaults delete org.eduvpn.app SUHasLaunchedBefore
    $ rm -rf ~/Library/Application\ Support/eduVPN/
    $ rm -rf ~/Library/Caches/org.eduvpn.app/
    $ rm -f ~/Library/Preferences/org.eduvpn.app.plist

All done!
