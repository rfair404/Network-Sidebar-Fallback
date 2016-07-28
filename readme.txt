=== Network Sidebar Widgets ===
Contributors: rfair404
Donate link: http://q21.co/donate
Tags: multisite, widget, sidebar
Requires at least: 4.0
Tested up to: 4.4
Stable tag: 0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enables Multisite Networks to create "fallback" widgets that can be used across-network when sites don't have any widgets.

== Description ==

In the multisite context, creating widgets that are used throughout all of the sites in a network can be difficult and painstaking (at best). 
This plugin gives network administrators the ability to create "fallback widgets" that will be used as the default unless over-ridden by individual sites.

How the plugin works

*   When a widget is created on the "main site" (e.g. site ID 1) the output is cached.
*   When a sites other than the "main site" (e.g. site ID != 1) call a sidebar that doesn't have any active widgets, the cached output from the main site will be used.
*   The plugin may not (read as "probably won't") work with highly dynamic widgets
*   This is the initial release, so please try it out before you use it in a live site.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/network-sidebar-fallback` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Appearance > Widgets section to create widgets for the primary site (site ID 1)


== Frequently Asked Questions ==

=Will this work with *fill-in-your-theme-name-here*?=

I have no idea, I haven't tested it. 

=Will this work with *fill-in-your-plugin-name-here*?=

I have no idea, I haven't tested it. 

=Will you add features to this plugin?=

Possibly, I will always consider suggestions :)


== Screenshots ==

None available at this time.

== Changelog ==

= 0.1 =
* Initial realease to WordPress plugin repository.

== Upgrade Notice ==


= 0.1 =
Please use the initial release.

