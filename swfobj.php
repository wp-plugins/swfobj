<?php
/*
Plugin Name: SwfObj
Plugin URI: http://svn.wp-plugins.org/swfobj/
Description: Easily insert Flash media with shortcode. Uses the SWF Object 2.0 library for greater browser compatability.
Version: 0.1
Author: Matt Carpenter
Author URI: http://orangesplotch.com/
*/

if (!class_exists("SwfObj")) {

class SwfObj {

	var $registered_objects; // holds all swf objects in the content
	var $admin_options_saved = 'SwfObjAdminOptions';

	// Constructor
	function SWFObj() {
		load_plugin_textdomain( 'swfobj', PLUGINDIR.'/'.dirname( plugin_basename(__FILE__) ) );

		global $registered_objects;
		$registered_objects = array();
	}

	// Return an array of options
	function get_options() {
		$admin_options = array( 'height' => '300',
		                        'width' => '400',
		                        'alt' => '<p>'.__('The Flash plugin is required to view this object.', 'swfobj').'</p>',
		                        'allowfullscreen' => 'false',
		                        'required_player_version' => '8.0.0',
	 	                        'express_install_swf' => get_bloginfo('wpurl').'/wp-content/plugins/swfobj/'.'expressInstall.swf' );
		$saved_options = get_option($this->admin_options_saved);
		if (!empty($saved_options)) {
			foreach($saved_options as $key => $val) {
				$admin_options[$key] = $val;
			}
		}
		update_option($this->admin_options_saved, $admin_options);
		return $admin_options;
	}

	function init() {
		$this->get_options();
		?>
		<div class="updated"><p><strong><?php _e('SwfObj Initialized', 'swfobj'); ?></strong></p></div>
		<?php
	}

	// Add SWF Object Javascript file to the page header
	function swfobj_header() {
		global $registered_objects;

		echo '

		<!-- SwfObj Plugin version '.SWFOBJ_VERSION.' -->
		<script type="text/javascript" src="'.get_bloginfo('wpurl').'/wp-content/plugins/swfobj/swfobject.js"></script>'."\n";
	}

	// Add Javascript to end of page to register all swf objects.
	function swfobj_footer() {
		global $registered_objects;

		// register any swf files on the page
		if (count($registered_objects) > 0) {
			echo '
			<script type="text/javascript">';

			foreach ($registered_objects as $swf) {
				echo '
				swfobject.registerObject("'.$swf['id'].'", "'.$swf['required_player_version'].'", "'.$swf['express_install_swf'].'");';
			}
			echo '
			</script>'."\n";
		}
	}

	// [swfobj] shortcode handler
	function swfobj_func($atts, $content='') {
		global $registered_objects;
		$defaults = $this->get_options();

		extract(shortcode_atts(array( 'src' => '',
		                              'width' => $defaults['width'],
		                              'height' => $defaults['height'],
		                              'alt' => $defaults['alt'],
		                              'allowfullscreen' => $defaults['allowfullscreen'],
		                              'id' => 'swfobj_'.count($registered_objects),
		                              'name' => false,
		                              'class' => false,
		                              'align' => false,
		                              'required_player_version' => $defaults['required_player_version'],
		                              'express_install_swf' => $defaults['express_install_swf'] /*,
// these options are not yet supported
    * play
    * loop
    * menu
    * quality
    * scale
    * salign
    * wmode
    * bgcolor
    * base
    * swliveconnect
    * flashvars
    * devicefont [ http://www.adobe.com/cfusion/knowledgebase/index.cfm?id=tn_13331 ]
    * allowscriptaccess [ http://www.adobe.com/cfusion/knowledgebase/index.cfm?id=tn_16494 ] and [ http://www.adobe.com/go/kb402975 ]
    * seamlesstabbing [ http://www.adobe.com/support/documentation/en/flashplayer/7/releasenotes.html ]
    * allownetworking [ http://livedocs.adobe.com/flash/9.0/main/00001079.html ] 
*/
		), $atts));

		// Add this object to the array so it will be registered in the header
		$registered_objects[]  = array('id' => $id, 'required_player_version' => $required_player_version, 'express_install_swf' => $express_install_swf);

		$swfobj = '
    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" id="'.$id.'" width="'.$width.'" height="'.$height.'"'.($class?' class="'.$class.'"':'').($align?' align="'.$align.'"':'').($name?' name="'.$name.'"':'').'>
      <param name="movie" value="'.$src.'" />'.(($allowfullscreen=='true')?'
      <param name="allowFullScreen" value="true" />':'').'
      <!--[if !IE]>-->
      <object type="application/x-shockwave-flash" data="'.$src.'" width="'.$width.'" height="'.$height.'"'.(($allowfullscreen=='true')?' allowFullScreen="true"':'').'>
      <!--<![endif]-->
        <p>'.$alt.'</p>
      <!--[if !IE]>-->
      </object>
      <!--<![endif]-->
    </object>
';

		return $swfobj;
	}

	function swfobj_options_page() {
		$options = $this->get_options();
		if ($_POST['options_update']) {
			// Update current options to the values submitted
			foreach ($options as $option => $value) {
				if (isset($_POST[$option])) {
					$options[$option] = $_POST[$option];
				}
			}
			update_option($this->admin_options_saved, $options);
			?>
			<div class="updated"><p><strong><?php _e('Options Updated', 'swfobj'); ?></strong></p></div>
			<?php
		}

/*
		'name' => false,
		'class' => false,
		'align' => false,
*/

		?>
<style type="text/css">
.form-table tr.odd { background: #f3F9Ff; }
</style>
<div class="wrap">
<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<h2><?php _e('SwfObj Default Settings', 'swfobj'); ?></h2>
      <input type="hidden" name="options_update" value="1" />

      <table class="form-table">
      <tr>
        <th scope="row" valign="top"><?php _e('Default Width', 'swfobj'); ?></th>
	<td><input type="text" name="width" value="<?php echo $options['width']; ?>" size="40" /></td>
	<td><em><?php _e('Default width of embedded Flash content.', 'swfobj'); ?></em></td>
      </tr>
      <tr class="odd">
        <th scope="row" valign="top"><?php _e('Default Height', 'swfobj'); ?></th>
	<td><input type="text" name="height" value="<?php echo $options['height']; ?>" size="40" /></td>
	<td><em><?php _e('Default height of embedded Flash content.', 'swfobj'); ?></em></td>
      </tr>
      <tr>
        <th scope="row" valign="top"><?php _e('Alternative Content', 'swfobj'); ?></th>
	<td><input type="text" name="alt" value="<?php echo $options['alt']; ?>" size="40" /></td>
	<td><em><?php _e('Alternative HTML content to display if Flash plugin is not installed in the browser.', 'swfobj'); ?></em></td>
      </tr>
      <tr class="odd">
        <th scope="row" valign="top"><?php _e('Required Flash Player', 'swfobj'); ?></th>
	<td><input type="text" name="required_player_version" value="<?php echo $options['required_player_version']; ?>" size="40" /></td>
	<td><em><?php _e('Default minimum Flash player required by the browser.', 'swfobj'); ?></em></td>
      </tr>
      <tr>
        <th scope="row" valign="top"><?php _e('Object CSS Class', 'swfobj'); ?></th>
	<td><input type="text" name="class" value="<?php echo $options['class']; ?>" size="40" /></td>
	<td><em><?php _e('The CSS class to apply to the embedded Flash object.', 'swfobj'); ?></em></td>
      </tr>
      <tr class="odd">
        <th scope="row" valign="top"><?php _e('Express Install Swf', 'swfobj'); ?></th>
	<td><input type="text" name="express_install_swf" value="<?php echo $options['express_install_swf']; ?>" size="40" /></td>
	<td><em><?php _e('Swf shown when viewer needs to upgrade their player.', 'swfobj'); ?></em></td>
      </tr>
      <tr>
        <th scope="row" valign="top"><?php _e('Allow Fullscreen Mode', 'swfobj'); ?></th>
	<td>
	    <label for="allowfullscreen_yes"><input type="radio" id="allowfullscreen_yes" name="allowfullscreen" value="true"<?php if ($options['allowfullscreen'] == 'true'): ?> checked="checked"<?php endif; ?> /> <?php _e('Yes', 'swfobj'); ?></label> &nbsp; &nbsp; &nbsp;
	    <label for="allowfullscreen_no"><input type="radio" id="allowfullscreen_no" name="allowfullscreen" value="false"<?php if ($options['allowfullscreen'] == 'false'): ?> checked="checked"<?php endif; ?> /> <?php _e('No', 'swfobj'); ?></label>
	<td><em><?php _e('Allow fullscreen mode by default.', 'swfobj'); ?></em></td>
      </tr>
      </table>

      <p class="submit">
      	 <input type="submit" name="Submit" value="<?php _e('Update Defaults', 'swfobj'); ?>" />
      </p>
</form>
</div>
		<?php
	}

} // end SwfObj class

}

if (class_exists("SwfObj")) {
	$swfobj = new SwfObj();
}

//Initialize the admin panel
if (!function_exists("swfobj_ap")) {
    function swfobj_ap() {
        global $swfobj;
        if (!isset($swfobj)) {
            return;
        }
        if (function_exists('add_options_page')) {
            add_options_page(__('SwfObj Default Settings', 'swfobj'), 'SwfObj', 8, basename(__FILE__), array(&$swfobj, 'swfobj_options_page'));
        }
    }   
}

// Actions and Filters
if (isset($swfobj)) {

	// Actions
	add_action('wp_head', array(&$swfobj, 'swfobj_header'), 100);
	add_action('wp_footer', array(&$swfobj, 'swfobj_footer'), 100);
	add_action('admin_menu', 'swfobj_ap', 100);
	add_action('activate_swfobj/swfobj.php',  array(&$swfobj, 'init'));

	// Filters

	// Shortcodes
	add_shortcode('swfobj', array(&$swfobj, 'swfobj_func'));
}

?>