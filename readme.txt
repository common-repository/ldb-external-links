=== Plugin Name ===
Contributors: ldebrouwer
Tags: target, blank, new, window, tab, external, link, links, anchor, anchors
Requires at least: 2.8
Tested up to: 3.0.1
Stable tag: 1.1

LDB External Links turns all your links with target="_blank" into rel="external" and uses Javascript to open them in a new window or tab.

== Description ==

LDB External Links turns all your links with target="_blank" into rel="external" and uses Javascript to open them in a new window or tab.

== Installation ==

1. Upload the folder `ldb-external-links` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Presto, Your done! No configuration needed.

== Frequently Asked Questions ==

= Why isn't it working? =

You probably forgot to add or either removed the wp_head() from your header.php file.

== Changelog ==

= 1.1 =
* Added a configuration page so you can choose the value of the rel attribute.
= 1.0 =
* First version of the plugin.
