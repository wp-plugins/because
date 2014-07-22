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

 * @copyright 2014 TeamBecause LLC

 *

 * @wordpress-plugin

 * Plugin Name:       Because

 * Plugin URI:        www.teambecause.com

 * Description:       Polling + Comments

 * Version:           1.1.5

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

/*

 * Register hooks that are fired when the plugin is activated or deactivated.

 * When the plugin is deleted, the uninstall.php file is loaded.

 *

 * @TODO:

 *

 * - replace Social_Polling with the name of the class defined in

 *   `class-social-polling.php`

 */

register_activation_hook( __FILE__, array( 'Social_Polling', 'activate' ) );

register_deactivation_hook( __FILE__, array( 'Social_Polling', 'deactivate' ) );




function social_poll_activate() {
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
}//social_poll_activate
register_activation_hook( __FILE__, 'social_poll_activate' );



//COMEBACKVERB

register_activation_hook( __FILE__, array( 'spComments', 'activate' ) );

register_deactivation_hook( __FILE__, array( 'spComments', 'deactivate' ) );

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

