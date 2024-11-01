=== Simple Accessible Spoilers ===
Contributors: seshelby
Donate link: 
Tags: shortcode, spoiler, accordion
Requires at least: 3.9.1
Tested up to: 6.6
Requires PHP: 5.6
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Stable tag: trunk 

== Description ==

Create fully accessible content spoilers or accordions with a shortcode.

1. Fully accessible to screen reader users
1. Creates a flexible spoiler shortcode
1. Define groups of accordions to close open accordions when another in the same group is opened.
1. Override design in theme CSS files

== Sample Code ==
`
[spoiler title="Sample Code" initial_state="expanded" tag="h2" group="a"]
Include content here
[/spoiler]
`

== Attributes ==
title: should include the clickable text to be displayed in your accordion 
initial_state: values include collapsed or expanded, default to "collapsed"
group: any alphanumeric value. used to define a group of accordions. when one accordion is opened all other items in the group will be closed.
tag: values include any typical html tag but a heading tag should be used for accessibility, defaults to H2

== Installation ==

1. Install via WordPress Dashboard or upload `simple-accessible-spoilers.zip`;
2. Activate the plugin through the 'Plugins' menu in WordPress;
3. Use "spoiler" shortcode in your content;

== Frequently Asked Questions ==

= How can I customize design of the spoiler? =
Override the styles found in `styles/simple-accessible-spoilers-styles.css` in your theme css files

== Changelog ==
= 1.0.13 =
1. added active and in focus styles for selected spoiler header
1. removed role from heading tags and enclosed the heading text inside a button tag
1. replaced css content for dashicon arrow with span element

= 1.0.12 =
1. corrected unescaped output variables

= 1.0.11 =
1. removed check for post before enqueueing styles and scripts to allow the shortcode to run in archive templates
1. changed to expansion speed of accordians to slow
1. modernized the look of the spoilers but adding rounded corners and updated icons
1. corrected page scroll when space bar is used to toggle accordian

= 1.0.10 =
1. corrected aria-expanded not updating on non-active elements when another element in the same group is activated

= 1.0.9 =
1. corrected spoiler titles being stripped when certain accented characters exists in the title

= 1.0.8 =
1. added option to customize shortcode

= 1.0.7 =
1. changed spoiler toggle role to link to correct validation issue

= 1.0.6 =
1. Added attribute input validation

= 1.0.5 =
1. Added option to group accordions so that opening a new accordion will close opened items in the same list.

= 1.0.4 =
1. Wordpress 5.5 Compatibility release

= 1.0.3 =
1. corrected bug resulting in initial state not displaying correctly

= 1.0.2 =
1. corrected php not object error when post object is not found in a page
1. added jquery dependency to wp_register_script to correct jquery not being loaded in some themes

= 1.0.1 =
1. corrected bug with scripts not enqueue as expected
1. added dashboard messages
