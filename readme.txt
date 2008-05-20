=== SwfObj Plugin ===
Contributors: Matt Carpenter
Donate link: http://orangesplotch.com/freelunch
Tags: embed, flash, flex, shortcode, swf, swfobject
Requires at least: 2.5
Tested up to: 2.6
Stable tag: 0.2

Insert Flash content into WordPress using shortcodes.

== Description ==

This plugin enables inserting flash content into WordPress posts and pages with shortcode. The resulting embedded Flash implements the **SWFObject** library for XHTML compliance and cross-browser compatibility. 

= Features =

*	Easy install
*	Insert Flash movie with simple short code
*	Support most Flash param options including allowFullscreen
*       Granular level of control allows easy overriding of default options
*	Generate `<object>` code for RSS compatibility	
*	Uses SWFObject 2.0

For inserting Flash content into a post or page use:

`[swfobj src="movie.swf"]`
`[swfobj src="movie.swf" height="250" width="400"]`

For more information visit the [plugin website](http://orangesplotch.com/blog/swfobj/ "plugin webpage")


== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the `swfobj` directory and its contents to the `/wp-content/plugins/swfobj/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Set the default options for your embedded objects in the `Options > SwfObj` page.
3. Use the swfobj shortcode in your posts.

For detailed instructions including a list of available attributes, visit the [plugin website](http://orangesplotch.com/blog/swfobj/ "plugin webpage")

== Implementation ==

The following attributes are available for use in the swfobj shortcode. If you do not include an attribute in your shortcode, the default value is used if available. Otherwise the attribute is simply not included in the embedded content.

= Attributes =

* **src** *(required)* The URL of your swf
* **height** The height of your object. *Default is 400px.*
* **width** The width of your object. *Default is 300px.*
* **alt** Alternative content to display.
* **allowfullscreen** Whether fullscreen mode is enabled. *Default is false.*
* **id** The id to use on the object.
* **name** The name to use on the object.
* **class** The class name to use on the object.
* **align** The alignment of the object.
* **required_player_version** The minimum Flash player required to play the object.*Default is 8.0.0*
* **express_install_swf** The swf to replace the object with if the viewer doesn't have the minimum Flash player installed.

= Examples =
`[swfobj src="movie.swf"]`
`[swfobj src="movie.swf" height="250" width="400"]`

For more detailed instructions, visit the [plugin website](http://orangesplotch.com/blog/swfobj/ "plugin webpage")

== Version History ==

= Version 0.1 =
* Initial release.

== Screenshots ==
No screenshots available yet.

== Frequently Asked Questions ==
Haven't gotten any questions yet, let alone frequent ones.