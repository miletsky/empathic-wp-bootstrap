## Overview
Bootstrap to create WordPress based projects  
WordPress version - 4.1

## Supplementary stuff
Consider using https://github.com/miletsky/wordpress-html5-boilerplate as your theme bootstrap

## Structure
* `public` dir - wordpress itself, this should be the root folder of your virtual host.
* `private` dir - sql dumps & other stuff which souldn't be accessible over http.

## Bare installation
* Configure virtual host with root in public
* Pass through WordPress installer
* Use import/export for database migrations

## Installation from existing dump
* Create a database and import there any wordpress db, it will be completely wiped with import script. Or manually create `wp_options` table with the 'siteurl' option.
* Update 'siteurl' to your actual domain.
* Update `public/wp-config.php` with your actual database credentials.
* Run `php private/dump/import.php`, note that it requres the wp-config.php and `wp_options` table with actual siteurl.
* Create `public/wp-content/uploads`, `chown www-data.www-data public/wp-content/uploads && chmod 755 public/wp-content/uploads`.
