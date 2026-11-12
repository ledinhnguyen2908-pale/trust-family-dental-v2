# Trust Family Dental

[Hướng dẫn cài đặt và khôi phục website (tiếng Việt)](HUONG-DAN.md)

The WordPress document root is `app/public`. The repository contains the WordPress files, child themes, redistributable plugins, and site media. The `conf` directory contains Local server templates.

To restore the existing site, use a trusted copy of its database and create `app/public/wp-config.php` from `wp-config-sample.php` with the destination database settings and fresh WordPress salts. Install Flatsome from the licensed package. Install Duplicator Pro and the All-in-One WP Migration Unlimited Extension from licensed packages if you need those migration tools.

Keep the database dump and migration archives in private storage. They are excluded from Git.
