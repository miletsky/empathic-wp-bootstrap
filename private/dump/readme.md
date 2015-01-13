On export script makes a full db dump and replaces site url with specific token.
On import it takes the site url from the current db, replaces the token in the dump with this site url
and imports it. Also, there is a dump.json file which contains serialized data.

Note: You must have already installed database. The scripts take db setting from current wp-config.php.


Steps to install the website with this script:
1. Create a database and import there any wordpress db, it will be completely wiped with import script. Or manually create wp_options table with the 'siteurl' option.
2. Update 'suteurl' to your actual domain.
3. Update public/wp-config.php with your actual database credentials.
4. Run `php private/dump/import.php`, note that it requires the wp-config.php and wp_options table with actual siteurl.
5. Create public/wp-content/uploads, `chown www-data.www-data public/wp-content/uploads && chmod 755 public/wp-content/uploads`.


How to export:
1. Run `php private/dump/export.php` in command line.
2. Commit the dump.sql & dump.json files (they should be updated if any changes in the db have taken place).


How to import:
0. git pull
1. Run in console 'php private/dump/import.php'.
2. ...
3. Profit!

Note: before import this script creates a backup of the existing database, look for the corresponding message in your console output.


Handling uploads with the import/export script:
This script can also create a zip of wp-content/uploads folder. To export uploads run
`php private/dump/export.php -u`
It will create private/dump/data/uploads.zip file.

Note: this file is in the .gitignore because it can be quite big. To download it run
`scp user@server:/abs-path-to-the-site/private/dump/data/uploads.zip private/dump/data/uploads.zip`
Or download it in any other way convenient for you like ftp into private/dump/data.

To import uploads run
`php private/dump/import.php -u`
The import will work as usual, it will just unzip uploads.zip file (if it exists) to the wp-content/uploads in addition.
