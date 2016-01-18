# XLR(8) Sign-in and attendance portal

**TL;DR:** This is the portal through which children, parents and volunteers can manage and track attendance, behavior and homework for Urban Impact's XLR(8) tutoring program in Bridgeport, CT.

To learn more about Urban Impact, visit [their website](http://urbanimpactct.org/).

## Getting started

1. Configure Apache to allow use of `mod_rewrite` in .htaccess files. This requires enabling the rewrite module and setting `AllowOverride` to `All` in your `httpd.conf`.
1. Create a MySQL database. Although we are using PDO, some MySQL specific features are used right now.
1. Copy src/XLR8/Configuration.example.php to src/XLR8/Configuration.php
1. Edit !$ to match your database configuration.
1. Merge src/XLR8/database.sql
1. Run `composer install` in the application's root to install dependencies.

## Contributing

Code contributions are very welcome. To contribute code to this project, use the normal GitHub workflow of pushing to a new branch and creating pull requests off of that branch.

## License

Given that this project includes a framework as well as an actual application, I'm licensing it under the LGPLv3. See the file `COPYING` for details.

## Author/contact

Dan Fuhry <dan@fuhry.com>
