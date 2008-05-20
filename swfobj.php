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
		load_plugin_textdomain( 'swfobj' );

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

	function addMediaButton() {
 		global $post_ID, $temp_ID;
		$uploading_iframe_ID = (int) (0 == $post_ID ? $temp_ID : $post_ID);
		$media_upload_iframe_src = "media-upload.php?post_id=$uploading_iframe_ID";

		$media_swfobj_iframe_src = apply_filters('media_swfobj_iframe_src', "$media_upload_iframe_src&amp;type=flash");
		$media_swfobj_title = __('Add Flash content', 'swfobj');

		echo '<a href="'.$media_swfobj_iframe_src.'&amp;TB_iframe=true&amp;height=500&amp;width=640" class="thickbox" title="'.$media_swfobj_title.'"><img src="'.get_bloginfo('wpurl').'/wp-content/plugins/swfobj/'.'media-button-flash.gif" alt="'.$media_swfobj_title.'"></a>';
	}

	function media_upload_flash() {
		if ( isset($_POST['html-upload']) && !empty($_FILES) ) {
			// Upload File button was clicked
			$id = media_handle_upload('async-upload', $_REQUEST['post_id']);
			unset($_FILES);
			if ( is_wp_error($id) ) {
				$errors['upload_error'] = $id;
				$id = false;
			}
		}

		if ( !empty($_POST['insertonlybutton']) ) {
			$href = $_POST['insertonly']['href'];
			if ( !empty($href) && !strpos($href, '://') )
				$href = "http://$href";
			$title = attribute_escape($_POST['insertonly']['title']);
			if ( empty($title) )
				$title = basename($href);
			if ( !empty($title) && !empty($href) )
				$html = "[swfobj src='$href' title='$title']";
			return media_send_to_editor($html);
		}

		if ( !empty($_POST) ) {
			$return = media_upload_form_handler();

			if ( is_string($return) )
				return $return;
			if ( is_array($return) )
				$errors = $return;
		}

		if ( isset($_POST['save']) )
			$errors['upload_notice'] = __('Saved.');

		return wp_iframe( 'media_upload_type_form', 'flash', $errors, $id );
	}

	function modify_post_mime_types($post_mime_types) {
		$post_mime_types['application/x-shockwave-flash'] = array(__('Flash', 'swfobj'), __('Manage Flash', 'swfobj'), __ngettext_noop('Flash (%s)', 'Flash (%s)', 'swfobj'));
		return $post_mime_types;
	}

	function modify_media_send_to_editor($html) {
		if ( isset($_POST['send']) ) {
			$keys     = array_keys($_POST['send']);
			$send_id  = (int) array_shift($keys);
			$flashobj = $_POST['attachments'][$send_id];
			
			$url   = $flashobj['url'];
			$title = stripslashes( htmlspecialchars ($flashobj['post_title'], ENT_QUOTES));
			$alt   = stripslashes( htmlspecialchars ($flashobj['post_content'], ENT_QUOTES));

			$html  = '[swfobj src="'.$url.'"'.( ($alt != '') ? ' alt="'.$alt.'"' : '' ).'] ';
		}
		return $html;
	}

	function flash_attachment_fields_to_edit($form_fields, $post) {
		if ( substr($post->post_mime_type, -5) == 'flash' ) {
			$form_fields['post_title']['required'] = true;
			unset( $form_fields['post_excerpt'] ); // $form_fields['post_excerpt']['label'] = __('Caption');
			// $form_fields['post_excerpt']['helps'][] = __('Not currently used.'); // 'Alternate text, e.g. "The Mona Lisa"'

			$form_fields['post_content']['label']   = __('Alternate html');
			$form_fields['post_content']['helps'][] = __('Displayed when Flash is unavailable, e.g. "&lt;p&gt;Cool Flash game.&lt;/p&gt;"');
		}
		return $form_fields;
	}

} // end SwfObj class

}

if (class_exists("SwfObj")) {
	$swfobj = new SwfObj();
}

function type_form_flash() {
	return '
	<table class="describe"><tbody>
		<tr>
			<th valign="top" scope="row" class="label">
				<span class="alignleft"><label for="insertonly[href]">' . __('Flash URL') . '</label></span>
				<span class="alignright"><abbr title="required" class="required">*</abbr></span>
			</th>
			<td class="field"><input id="insertonly[href]" name="insertonly[href]" value="" type="text"></td>
		</tr>
		<tr>
			<th valign="top" scope="row" class="label">
				<span class="alignleft"><label for="insertonly[title]">' . __('Title') . '</label></span>
				<span class="alignright"><abbr title="required" class="required">*</abbr></span>
			</th>
			<td class="field"><input id="insertonly[title]" name="insertonly[title]" value="" type="text"></td>
		</tr>
		<tr><td></td><td class="help">' . __('Link text, e.g. "Lucy on YouTube"') . '</td></tr>
		<tr>
			<td></td>
			<td>
				<input type="submit" class="button" name="insertonlybutton" value="' . attribute_escape(__('Insert into Post')) . '" />
			</td>
		</tr>
	</tbody></table>
';
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
        add_action('media_buttons', array(&$swfobj, 'addMediaButton'), 20);
        add_action('media_upload_flash', array(&$swfobj, 'media_upload_flash'));

	// Filters
	add_filter('post_mime_types', array(&$swfobj, 'modify_post_mime_types'));
	add_filter('async_upload_flash', 'get_media_item', 10, 2);
	add_filter('media_send_to_editor', array(&$swfobj, 'modify_media_send_to_editor'));
	add_filter('attachment_fields_to_edit', array(&$swfobj, 'flash_attachment_fields_to_edit'), 10, 2);

	// Shortcodes
	// check if shortcodes exist just so this plugin doesn't kill WordPress on versions < 2.5
	if ( function_exists('add_shortcode') ) {
		add_shortcode('swfobj', array(&$swfobj, 'swfobj_func'));
	}

}

?>