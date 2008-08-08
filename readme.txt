=== SwfObj Plugin ===
Contributors: Matt Carpenter
Donate link: http://orangesplotch.com/freelunch
Tags: embed, flash, flex, insert, media button, shortcode, swf, swfobject, upload
Requires at least: 2.5
Tested up to: 2.6
Stable tag: 0.4

Insert Flash content into WordPress using shortcodes.

== Description ==

This plugin enables inserting flash content into WordPress posts and pages with shortcode. The resulting embedded Flash implements the **SWFObject** library for XHTML compliance and cross-browser compatibility. 

= Features =

*	Easy install
*	Upload and embed Flash media using WordPress's native media tools
*	Insert Flash objects with simple short code
*	Supports all Flash param options including flashvars, and allowFullscreen
*       Granular level of control allows easy overriding of default options
*	Generates `<object>` code for RSS compatibility	
*	Uses SWFObject 2.0 for greater browser support

Insert Flash content into a post or page using the Flash media button, or simple shortcode:

`[swfobj src="movie.swf"]`
`[swfobj src="movie.swf" height="250" width="400"]`

For more information visit the [plugin website](http://orangesplotch.com/blog/swfobj/ "plugin webpage")


== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the `swfobj` directory and its contents to the `/wp-content/plugins/swfobj/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Set the default options for your embedded objects on the `Settings > SwfObj` page.
4. Add Flash media using the new Flash media button above the post editor.
3. Use the swfobj shortcode in your posts.

For detailed instructions including a list of available attributes, visit the [plugin website](http://orangesplotch.com/blog/swfobj/ "plugin webpage")

== Implementation ==

Click on the Flash media button above a post to upload and embed Flash media in your current post or page. When inserting a Flash object, the necessary shortcode is automatically inserted into the post editor. Additionally, you can manually create and edit swfobj shortcode. 

The following attributes are available for use in the swfobj shortcode. If an attribute is not included, the default value is used if available. Otherwise the attribute is simply not included in the embedded content. To modify attributes, go to the `Settings > SwfObj` page.

= Main Attributes =
* **src** *(required)* The URL of your swf
* **height** The height of your object. *Default is 400px.*
* **width** The width of your object. *Default is 300px.*
* **alt** Alternative content to display.
* **id** The id to use on the object.
* **name** The name to use on the object.
* **class** The class name to use on the object.
* **align** The alignment of the object.
* **required\_player\_version** The minimum Flash player required to play the object. *Default is 8.0.0*
* **express\_install\_swf** The swf to replace the object with if the viewer doesn't have the minimum Flash player installed.
* **getvars** GET variables to be appended to the .swf file in the src attribute.

= Examples =
`[swfobj src="movie.swf"]`
`[swfobj src="movie.swf" height="250" width="400"]`


= Additional Attributes =

Additionally, the following Flash specific parameters can be set.

* **play**
* **loop**
* **menu**
* **quality**
* **scale**
* **salign**
* **wmode**
* **bgcolor**
* **base**
* **swliveconnect**
* **flashvars**
* **devicefont**
* **allowscriptaccess**
* **seamlesstabbing**
* **allowfullscreen**
* **allownetworking**

For more detailed instructions, visit the [plugin website](http://orangesplotch.com/blog/swfobj/ "plugin webpage")

== Version History ==

= Version 0.4 =
* Added support for all available Flash parameters.

= Version 0.3 =
* Better support of auto-generated shortcode from the media library.
* Advanced embedding options available in media library popup.

= Version 0.2 =
* Flash media button allows uploading and inserting Flash media into posts and pages
* Flash media tab in the WordPress media library
* Inserting a Flash object into a post auto generates the proper shortcode. 
* Added multiple language support

= Version 0.1 =
* Initial release.

== Screenshots ==
1. SwfObj adds a Flash media type to the media library.
2. SwfObj allows easy embedding of Flash content into posts using media buttons.
3. Advanced options for embedding Flash content are also available.
4. You can change the default settings for embedding Flash content.

== Frequently Asked Questions ==

= Why isn't it working? I'm using WP 2.3 =
This plugin uses WordPress's shortcode API. A feature which was added to WordPress in version 2.5. All versions of WordPress prior to this release, unfortunately, are incompatible with this plugin. Sorry.
