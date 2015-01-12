<?php

/**

 * The WordPress Plugin Boilerplate.

 *

 * A foundation off of which to build well-documented WordPress plugins that

 * also follow WordPress Coding Standards and PHP best practices.

 *

 * @package   Social_Polling

 * @author    santillotj@gmail.com

 * @license   GPL-2.0+

 * @link      http://www.teambecause.com

 * @copyright 2014 Conva LLC

 *

 * @wordpress-plugin

 * Plugin Name:       Conva

 * Plugin URI:        www.conva.us

 * Description:       Conva is a new commenting platform that increases audience engagement by providing new avenues for channelling audience discussions.

<<<<<<< .mine
 * Version:           1.6
=======
 * Version:           1.6
>>>>>>> .r969865

 * Author:            T.J. Santillo

 * Author URI:        http://profiles.wordpress.org/tjsantilo/
 * Text Domain:       social-polling-locale

 * License:           GPL-2.0+

 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt

 * Domain Path:       /languages

 * GitHub Plugin URI: https://github.com/<owner>/<repo>

 */



// If this file is called directly, abort.

if ( ! defined( 'WPINC' ) ) {

	die;

}



/*----------------------------------------------------------------------------*

 * Public-Facing Functionality

 *----------------------------------------------------------------------------*/



/*

 * @TODO:

 *

 * - replace `class-social-polling.php` with the name of the plugin's class file

 *

 */

require_once( plugin_dir_path( __FILE__ ) . 'public/class-social-polling.php' );

require_once( plugin_dir_path( __FILE__ ) . 'public/includes/class-sp_comments.php' );



// SETTINGS
define('BECAUSE_ADMIN_SERVER_URL', 'http://www.teambecause.com/pluginactivated.php <replace with your own location>
action');
define('BECAUSE_ADMIN_SECRET_TOKEN_NAME', 'secretToken');
define('BECAUSE_ADMIN_SECRET_TOKEN_VALUE', 'X-Secret-Token');


/*

 * Register hooks that are fired when the plugin is activated or deactivated.

 * When the plugin is deleted, the uninstall.php file is loaded.

 *

 * @TODO:

 *

 * - replace Social_Polling with the name of the class defined in

 *   `class-social-polling.php`

 */

register_activation_hook( __FILE__, 'because_plugin_activate' );
register_deactivation_hook( __FILE__, 'because_plugin_deactivate' );


/* executes the static activate functions from the social_polling and spComments classes to create new tables */
register_activation_hook( __FILE__, array( 'Social_Polling', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Social_Polling', 'deactivate' ) );
register_activation_hook( __FILE__, array( 'spComments', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'spComments', 'deactivate' ) );


function because_plugin_activate(){
	because_old_activation();
	$data = get_wp_data();
	$data['isActivation'] = '1';
	send_statistic_data($data);
}

function because_plugin_deactivate()
{
	$data = get_wp_data();
	$data['isActivation'] = '0';
	send_statistic_data($data);
}

function get_wp_data(){
	$site_url = get_site_url();
	$site_name = get_bloginfo('name');
	$site_ip_addr = get_ip();
	$data_arr = array(
		'siteIpAddress' => $site_ip_addr,
		'siteName' => $site_name,
		'siteUrl' => $site_url
	);
	return $data_arr;
}


function send_statistic_data($data)
{
	try {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, BECAUSE_ADMIN_SERVER_URL);
		curl_setopt($ch, CURLOPT_POST, 1);
		$header = array(BECAUSE_ADMIN_SECRET_TOKEN_NAME.': ' . 
		BECAUSE_ADMIN_SECRET_TOKEN_VALUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		$response = curl_exec ($ch);
		$error = curl_error($ch);
		curl_close ($ch);
	}
	catch (Exception $error) {
		$this->debug(__METHOD__, 'CURL error: ' . $error);
		return $response;
	}
}

function get_ip(){
	if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
		$ip = getenv("HTTP_CLIENT_IP");
	else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
		$ip = getenv("REMOTE_ADDR");
	else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
		$ip = $_SERVER['REMOTE_ADDR'];
	else
		$ip = "unknown";
	return($ip);
}


function because_old_activation(){
	ob_start();	
	//Site Name	
	$site_name = urlencode(get_option ('blogname'));
	//Site Url
	$site_url =  urlencode(get_option ('siteurl'));
	//Site Description
	$site_description =  urlencode(get_option('blogdescription'));	
	// Get cURL resource
	$curl = curl_init();
	// Set some options - we are passing in a useragent too here
	curl_setopt_array($curl, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => 'http://www.teambecause.com/pluginactivated.php?sitename='.$site_name.'&site_url='.$site_url.'&site_description='.$site_description,
		CURLOPT_USERAGENT => 'Codular Sample cURL Request'
	));
	// Send the request & save response to $resp
	$resp = curl_exec($curl);
	// Close request to clear up some resources
	curl_close($curl);	
	//exit;
	ob_end_clean();	
}






/*

 * @TODO:

 *

 * - replace Social_Polling with the name of the class defined in

 *   `class-social-polling.php`

 */

add_action( 'plugins_loaded', array( 'Social_Polling', 'get_instance' ) );



/*----------------------------------------------------------------------------*

 * Dashboard and Administrative Functionality

 *----------------------------------------------------------------------------*/



/*

 * @TODO:

 *

 * - replace `class-social-polling-admin.php` with the name of the plugin's admin file

 * - replace Social_Polling_Admin with the name of the class defined in

 *   `class-social-polling-admin.php`

 *

 * If you want to include Ajax within the dashboard, change the following

 * conditional to:

 *

 * if ( is_admin() ) {

 *   ...

 * }

 *

 * The code below is intended to to give the lightest footprint possible.

 */

if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-social-polling-admin.php' );

	add_action( 'plugins_loaded', array( 'Social_Polling_Admin', 'get_instance' ) );

}

