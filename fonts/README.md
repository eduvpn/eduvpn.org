# Web Fonts

Open Sans is licensed under the Apache 2.0 license by Google.

To use web fonts we use the `.woff2` format. We create them like this, on a 
modern Fedora installation:

    $ sudo dnf -y install woff2-tools
    $ sudo dnf -y install open-sans-fonts

The `open-sans-fonts` package comes with `.ttf` fonts, and not `.woff2`, but we 
can convert them! 

We need the following fonts:

* `OpenSans-Regular.ttf`
* `OpenSans-Bold.ttf`
* `OpenSans-Italic.ttf`
* `OpenSans-BoldItalic.ttf`

Copy them to the location where you want to write the `.woff2` files:

    $ mkdir -p ${HOME}/fonts
    $ cp /usr/share/fonts/open-sans/* ${HOME}/fonts
    $ cd ${HOME}/fonts
    $ for f in *; do woff2_compress ${f}; done
    $ rm *.ttf

Now, for the CSS. We use the `local` as well in case the user already has the
font installed on their system, no need to download it in that case!

    @font-face {
        font-family: "Open Sans";
        src: local("Open Sans"), url("../../fonts/eduVPN/OpenSans-Regular.woff2") format("woff2");
        font-weight: normal;
        font-style: normal;
    }

    @font-face {
        font-family: "Open Sans";
        src: local("Open Sans Bold"), url("../../fonts/eduVPN/OpenSans-Bold.woff2") format("woff2");
        font-weight: bold;
        font-style: normal;
    }

    @font-face {
        font-family: "Open Sans";
        src: local("Open Sans Italic"), url("../../fonts/eduVPN/OpenSans-Italic.woff2") format("woff2");
        font-weight: normal;
        font-style: italic;
    }

    @font-face {
        font-family: "Open Sans";
        src: local("Open Sans Bold Italic"), url("../../fonts/eduVPN/OpenSans-BoldItalic.woff2") format("woff2");
        font-weight: bold;
        font-style: italic;
    }

# Resources

As always, 
[MDN](https://developer.mozilla.org/en-US/docs/Learn/CSS/Styling_text/Web_fonts) 
is quite helpful. It recommends some (cloud) services, but we can do it also 
offline as shown above!
