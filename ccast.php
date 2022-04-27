<?php
/*
* Plugin Name: CCast
* Description:  Call CableCast APIs
* Author:      Keith Gudger
* Author URI:  http://www.github.com/kgudger
* License:     GPL2
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
*  */

defined( 'ABSPATH' ) or die( 'Ah ah ah, you didn\'t say the magic word' );

add_action('admin_menu', 'CCast_setup_menu');
add_action( 'admin_init', 'register_CCast_settings' );
 
function CCast_setup_menu(){
        add_options_page( 'CCast Setup Page', 'CCast', 'manage_options', 'CCast-plugin', 'CCast_options_page' );
}

function register_CCast_settings () { // register the API Key
	register_setting ('CCastPlugin', 'CCast_API_Key_settings');
	register_setting ('CCastPlugin', 'CCast_URL_settings');
	add_settings_section(
		'CCast_Plugin_section',
		__( 'CableCast Settings', 'wordpress'),
		'CCast_section_callback',
		'CCastPlugin'
	);

	add_settings_field(
		'CCast_URL_Key_field',
		__( 'CableCast URL Value', 'wordpress'),
		'CCast_url_render',
		'CCastPlugin',
		'CCast_Plugin_section'
	);
	add_settings_field(
		'CCast_API_Key_field',
		__( 'CableCast API Key Value', 'wordpress'),
		'CCast_api_render',
		'CCastPlugin',
		'CCast_Plugin_section'
	);
}
 
function CCast_api_render() {
	$options = get_option( 'CCast_API_Key_settings' );
	?>
	<input type='text' name='CCast_API_Key_settings[CCast_API_Key_field]' value='<?php echo $options['CCast_API_Key_field']; ?>'>
	<?php
}

function CCast_url_render() {
	$options = get_option( 'CCast_URL_settings' );
	?>
	<input type='text' name='CCast_URL_settings[CCast_URL_Key_field]' value='<?php echo $options['CCast_URL_Key_field']; ?>'>
	<?php
}

function CCast_section_callback () {
	echo __( 'Enter the CableCast URL and API Key here', 'wordpress');
}

/**
 * Display the options page and form
 */
function CCast_options_page() {
	?>
	<h2>CCast Settings Admin Page</h2>
	<form action='options.php' method='post'>
	<?php
	settings_fields( 'CCastPlugin' );
	do_settings_sections( 'CCastPlugin' );
	submit_button();
	?>
	</form>
	<h4>Usage</h4>
	<p>Insert the following shortcodes in pages where needed</p>
	<p><code>[ccast action="user"]</code><br>
	<span>Page for user scheduling of programs</span><p>
	</p></p>
	<p><code>[ccast action="admin"]</code><br>
	<span>Page to enter details for new userss</span><p>
	</p></p>
	<p><code>[wspin action="public"]</code><br>
	<span>Page for public to upload programs</span><p>
	</p></p>
	<p>Note: HTML responses are wrapped in a paragraph class styled
	in the theme appearance menu.  "cast_recent" and "cast_today"
	and "cast_list" are the associated classes.</p>
	<?php
}

add_shortcode('ccast', 'ccast_castapi');
include_once('includes/ccastpage.php');

/** Actual shortcode code
 *  @param $atts is an array of passed parameters, default null
 *  @param $content is any content in the shortcode
 *  @param $tag ??
 */
function ccast_castapi($atts=[], $content=null,$tag='') {
// sets defaults for $atts parameters
$a = shortcode_atts( array(
	'action' => "",
), $atts );
$rstring = ""; // return string, required in WP
if ( strtolower($a['action']) == 'user' ) { // user private programming page
	$checkArray = array();
/// a new instance of the derived class (from MainPage)
	$ccast = new ccastPage($db,$sessvar,$checkArray) ;
/// and ... start it up!
	$rstring = $ccast->main("CabelCast User Page", $uid, "", "");
} else if ( strtolower($a['action']) == 'admin' ) { // admin private page
	$rstring = "admin" ;  // count is hours into future
} else if ( strtolower($a['action']) == 'public' ) { // public prograrms
	$rstring = "public"; //
}
return $rstring;
}
?>
