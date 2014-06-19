=== Local Open Sans ===
Contributors: ThemeBoy
Tags: open sans, admin, speed, google, fonts, development, dashboard, local
Requires at least: 3.0
Tested up to: 3.9.1
Stable tag: 1.0
License: GPLv3 or later

Replace Open Sans with a local copy to speed up admin testing and development.

== Description ==

Local Open Sans contains copies of Open Sans fonts that WordPress loads from Google Fonts. The plugin deregisters the admin style that loads these fonts remotely and replaces it with a stylesheet that loads local copies of the same fonts.

Recommended for development when "fonts.googleapis.com" is adding time to admin pageloads. Not recommended for production (live sites) because it is almost always faster for your visitors to load fonts from Google or to use cached copies.

== Installation ==

= Minimum Requirements =
* WordPress 3.0 or greater
* PHP version 5.2.4 or greater
* MySQL version 5.0 or greater

= Automatic Installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t even need to leave your web browser. To do an automatic install of Local Open Sans, log in to your WordPress admin panel, navigate to the Plugins menu and click Add New.

In the search field type "Local Open Sans" and click Search Plugins. Once you’ve found the plugin you can view details about it such as the point release, rating and description. Most importantly of course, you can install it by simply clicking Install Now. After clicking that link you will be asked if you’re sure you want to install the plugin. Click yes and WordPress will automatically complete the installation.

= Manual Installation =

The manual installation method involves downloading the plugin and uploading it to your webserver via your favorite FTP application.

1. Download the plugin file to your computer and unzip it
2. Using an FTP program, or your hosting control panel, upload the unzipped plugin folder to your WordPress installation’s wp-content/plugins/ directory.
3. Activate the plugin from the Plugins menu within the WordPress admin.

= Upgrading =

Automatic updates should work like a charm; as always though, ensure you backup your site just in case.

== Changelog ==

= 1.0 =
* Initial release.