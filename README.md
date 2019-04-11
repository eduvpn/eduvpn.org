The [eduvpn.org](https://eduvpn.org) website!

Please submit a PR if you want to modify anything :)

Pages on the site can be found in `pages/` and blog posts in `posts/`.

To generate the pages on your own system:

    $ composer install
    $ php generate.php
    $ firefox output/index.html

To upload to the server (assuming you configured your SSH correctly and have
the right permissions): 

    $ sh upload.sh

