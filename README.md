The [eduvpn.org](https://eduvpn.org) website!

Please submit a PR if you want to modify anything :)

Pages on the site can be found in `pages/` and blog posts in `posts/`.

If you use a Linux system you can usually install 
[Composer](https://getcomposer.org/) using your package manager. 

    $ sudo yum install composer
    $ sudo dnf install composer
    $ sudo apt install composer

On other platforms, e.g. macOS, you can download it 
[here](https://getcomposer.org/composer.phar).

To install the dependencies:

    $ composer install

Or (when Composer was installed manually):

    $ php /path/to/composer.phar install

To generate the pages:

    $ php bin/generate.php

To view the generated site locally:

    $ firefox output/index.html

To upload to the server (assuming you configured your SSH correctly and have
the right permissions): 

    $ HOST=www.example.org ./upload.sh
