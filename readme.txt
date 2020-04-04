=== FreezeWP - Code Freeze Your WordPress Dashboard ===
Developer: M Asif Rahman
Donate link: http://wordpressfoundation.org/donate/
Contributors: Asif2BD
Tags: freeze WordPress, code freeze, freeze wp, read only, disable, disable comments, disable dashboard, migration, disable gutenberg, disable plugin
Requires at least: 4.8
Tested up to: 5.4
Requires PHP: 5.4
Stable tag: 0.1.0
License: GPLv3
License URI: https://opensource.org/licenses/GPL-3.0

FreezeWP is a simple plugin that temporarily puts your WordPress site into a "read only" - Code Freeze state. It's most useful when you are migrating a site. or just want to prevent changes.

== Description ==

FreezeWP is a simple plugin that temporarily does the following while activated:

* Disables adding/editing/deleting new content, media, themes, etc.
* Disables installing/activating/deactivating/updating/deleting all plugins (except this one)
* Disables new comments/trackbacks on all content items
* Removes notices to upgrade core or plugins
* Provides notice to dashboard users that any changes will be lost
* Provides generally "read only" access to the dashboard
* Also effective with Gutenberg & Elementor

These changes apply to all users (including admins). When deactivated, full functionality is restored. This plugin makes no database or site changes and has no settings. Simply deactivate/delete when no longer needed.

Activate this plugin on your old site when you're in the process of changing web hosts to prevent lost data due to delays in DNS changes, or as a simple short-term "lock down" for other reasons. Also can be network activated to apply to all network sites.

**Check My Other Plugins:**

* **[Essential Addons for Elementor](https://wordpress.org/plugins/essential-addons-for-elementor-lite/)** 
* **[NotificationX](https://wordpress.org/plugins/notificationx/)** 
* **[Twitter Cards Meta](https://wordpress.org/plugins/twitter-cards-meta/)** 
* **[Analytify - Ultimate Google Analytics Dashboard](https://wordpress.org/plugins/wp-analytify/)**
* **[WP Scheduled Posts](https://wordpress.org/plugins/wp-scheduled-posts/)**


== Installation ==

= Modern Way: =
1. Go to the WordPress Dashboard "Add New Plugin" section.
2. Search For "FreezeWP". 
3. Install, then Activate it.

This plugin makes no database or site changes and has no settings. Simply deactivate/delete when no longer needed.

== Frequently Asked Questions ==

= What is the purpose of this? =
This plugin was developed to aid multi-user sites when they are being moved from one web host to another. In this case, the following workflow may be helpful:

* Download a backup of your site (cPanel, etc.) and database. (Export and backup options are still available even if this plugin is already active.)
* Install the FreezeWP plugin on the old site, and activate it. This will alert others that content changes won't be accepted or will be lost.
* Upload/import your old site to your new one, and don't install the FreezeWP plugin there (unless you want to verify everything before returning the site to normal).
* Notify your users that when the "FreezeWP" message is removed, they may resume their work. When DNS changes are in effect, you'll be directed to the site that doesn't have it installed, and all should be well.

= How do I return my site and dashboard to normal? =
Just deactivate or delete the plugin. There are no changes made to the site or database nor any settings to worry about.

= Is it 100% bulletproof? =
Not exactly. Many of the restrictions are hidden options rather than disabled capabilities, to reduce complexity. Therefore this is not intended to be a 100% bulletproof solution to preventing changes to your site, as a user who is familiar with the URL syntax of the different commands may still make changes. Additionally, some plugins may continue to expose options that can be modified, though this should be rare. While not completely bulletproof in every circumstance, it is designed to keep the average user from making changes during a brief time frame (such as waiting for DNS changes to take effect).

= How do I change the text on the login screen or the alert message in the dashboard? =
Edit the values in the appropriate language file. If there isn't one for your locale, go ahead and create one.

= Can I use this on a Network (Multisite) install? =
Yes. The plugin can be network activated rendering all sites on the network effectively read-only. However, it does not support loading via the `mu-plugins` folder at this time.

= Why can users still do such-and-such while the plugin is active? =
Let me know what you discover is still available and I'll try to disable modifications to it where appropriate.

== Screenshots ==

1. FreeZWP(Plugin) Active (Dashboard)
2. FreeZWP(Plugin) Active (Plugin Page)
3. FreeZWP(Plugin) Active (Posts Page - No Option To Post)
4. FreeZWP(Plugin) Disable - All Back To Normal

== Changelog ==

= 0.1.0  =
* Initial release

== Upgrade Notice == 
* Initial Release


== Donation ==
You may donate to WordPress Foundation.