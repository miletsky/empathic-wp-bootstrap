# Wordress Project Bootstrap

## Structure
* `public` - Wordpress itself, this should be the root folder of your virtual host.
* `protected` - some stuff which shouldn't be accessible over http (is not used now but may be in the future).
    * `protected/other` - is ignored by git, some project related but not intended for commiting stuff can be put there

## Initial setup

* **New project:**
    * Install Wordpress to `public`
    * Set up multisite stuff in `public/wp-confing.php` in case of multisite instance (check **Multisite config** section below)

* **Existing project:**
    * Ask administrators for recent database dump and uploads from the server
    * Import the dump, unzip uploads to `public/wp-content/uploads`
    * Create `public/wp-confing.php`, specify db credentials, set up your local multisite in case of multisite instance (check **Multisite config** section below)
    * Update blog database with your local dev domain (see instructions below)

## Updating db with local dev domains
Two options are available:

* Using `wp-cli`

    Firstly, install wp-cli tool http://wp-cli.org. Manual for windows https://github.com/wp-cli/wp-cli/wiki/Alternative-Install-Methods#installing-via-composer.

    Then, use `search-replace` feature http://wp-cli.org/commands/search-replace

    Regular instance:

    `wp search-replace '<production_domain>' '<local_domain>'`

    Multisite instance:

    `wp search-replace --network '<production_domain>' '<local_domain>'`

    This method is preferred as it makes replacement in all tables including serialized data and reduces hassle with multisite stuff. Also, an sh script can be created in `protected/other` to perform a batch update with one command.

    Example:

    ```
    wp search-replace --network '<production_domain1>' '<local_domain1>'
    wp search-replace --network '<production_domain2>' '<local_domain2>'
    # etc...
    ```

* Manually

    * Update `site_url` and `home` options in the `wp_options` table
    * Fix urls in the blog posts content to your local dev domain with the following query

    ```
    UPDATE wp_posts SET post_content = REPLACE(post_content, '<local_domain>', '<production_domain>');
    ```

    In case of multisite, also do this:
    * Update particular blog url in the `wp_blogs` table with your local dev domain
    * Update `site_url` and `home` options in the `wp_<blog_id>_options` with the same local dev domain
    * Fix urls in the blog posts content to your local dev domain with the following query

    ```
    UPDATE wp_<blog_id>_posts SET post_content = REPLACE(post_content, '<local_domain>', '<production_domain>');
    ```

## Multisite config
Add the following to your `wp-config.php`, above the line `/* That's all, stop editing! Happy blogging. */`:

```
/* Multisite */
define( 'WP_ALLOW_MULTISITE', true );

define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', true);
define('DOMAIN_CURRENT_SITE', '<main_dev_site_domain>'); // sites.dev
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);

define('ADMIN_COOKIE_PATH', '/');
define('COOKIE_DOMAIN', '');
define('COOKIEPATH', '');
define('SITECOOKIEPATH', '');

define('NOBLOGREDIRECT', '<main_dev_site_url>'); // http://sites.dev
```
---

*Update this readme with particular project info*
