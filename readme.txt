=== Hide Plugins ===
Contributors: brianmiyaji
Tags: hide, hidden, plugins, admin, dashboard, secret, visibility, privacy, invisible, ninja
Requires at least: 3.0
Tested up to: 4.5
Stable tag: 1.0.4
License: GPLv2 or later

Hide installed plugins from clients and other admin users.

== Description ==

Hide Plugins is a light-weight plugin that gives a single admin user the ability to hide plugins prevent them from being activated, deactivated, or deleted by clients and other users, including administrators. By activating Hide Plugins, you will be able to see all plugins and a toggle to hide each plugin from other users on the Plugins page. Hide Plugins will always remain hidden.

Note that the dropdown on the Edit Plugins page will not be affected, since it does not have a filter to hook into. Hidden plugins will remain active, so traces of the plugin in areas other than the Plugins page (like options pages in the admin menu) will still be visible. If you also want to hide menus, we recommend using [Admin Menu Editor](https://wordpress.org/plugins/admin-menu-editor/).

== Installation ==

= Minimum Requirements =
* WordPress 3.0 or greater
* PHP version 5.2.4 or greater
* MySQL version 5.0 or greater

= Automatic Installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t even need to leave your web browser. To do an automatic install of Hide Plugins, log in to your WordPress admin panel, navigate to the Plugins menu and click Add New.

In the search field type "Hide Plugins" and click Search Plugins. Once you’ve found the plugin you can view details about it such as the point release, rating and description. Most importantly of course, you can install it by simply clicking Install Now. After clicking that link you will be asked if you’re sure you want to install the plugin. Click yes and WordPress will automatically complete the installation.

= Manual Installation =

The manual installation method involves downloading the plugin and uploading it to your webserver via your favorite FTP application.

1. Download the plugin file to your computer and unzip it
2. Using an FTP program, or your hosting control panel, upload the unzipped plugin folder to your WordPress installation’s wp-content/plugins/ directory.
3. Activate the plugin from the Plugins menu within the WordPress admin.

= Upgrading =

Automatic updates should work like a charm; as always though, ensure you backup your site just in case.

== Screenshots ==

1. Plugins page as viewed by Hide Plugin's master admin user. Hidden plugins appear in italics.
2. Plugins page as viewed by another admin user. Hidden plugins are not displayed.

== Frequently Asked Questions ==

= What does it hide? =

Hide Plugins does one thing: it prevent plugins from appearing on the Plugins page in your WordPress dashboard for all other users. Any other areas of the dashboard that may be affected by hidden plugins, like settings pages and dashboard widgts, will not be hidden.

= Which plugins can I hide? =

Hide Plugins will always be hidden by default, when it is active. You will see new action links under every plugin on the Plugins page. Click "Hide" to hide a plugin from other users. Click "Show" to unhide the plugin, so that it is visible to everyone again. Hidden plugins will be displayed in italics.

= Who does it hide plugins from? =

Hide Plugins will hide the chosen plugins from all other users, including admin users. The only person who will be able to see hidden plugins is you, the master user.

= How can I also hide admin menu links? =

Install the [Admin Menu Editor](https://wordpress.org/plugins/admin-menu-editor/) plugin to re-order, hide, or rename menus, add custom menus and more.

== Changelog ==

= 1.0.4 =
* Feature - Add plugin action links to multisite.

= 1.0.3 =
* Tweak - Display hidden plugin descriptions in italics.
* Localization - German translations added.
* Localization - Spanish translations added.
* Localization - Finnish translations added.
* Localization - French translations added.
* Localization - Italian translations added.
* Localization - Norwegian translations added.
* Localization - Dutch translations added.
* Localization - Portuguese translations added.
* Localization - Swedish translations added.

= 1.0.2 =
* Tweak - Display hidden plugin names in italics instead of appending " - Hidden" string.

= 1.0.1 =
* Localization - Japanese translations added.

= 1.0 =
* Feature - Save current user as Hide Plugins master on activation.
* Feature - Label hidden plugins and display actions links to master user.
* Feature - Hide selected plugins from all other users.