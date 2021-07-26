<?php
namespace WALDIR;

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link    https://github.com/wrdsb/wordpress-plugin-waldir
 * @since   1.0.0
 * @package WALDIR
 *
 * @wordpress-plugin
 * Plugin Name: WALDIR
 * Plugin URI: https://github.com/wrdsb/wordpress-plugin-waldir
 * Description: Custom meta fields and API endpoints for Sites and Users.
 * Version: 1.0.0
 * Author: WRDSB
 * Author URI: https://github.com/wrdsb
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: waldir
*/
// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

require_once 'vendor/autoload.php';

add_action( 'rest_api_init', 'create_waldir_rest_routes', 99 );
function create_waldir_rest_routes() {
	// Sites
	$controller = new REST\Routes\Sites;
	$controller->register_routes();

	// Users
	$controller = new REST\Routes\Users;
	$controller->register_routes();

	$controller = new REST\Routes\UserByEIN;
	$controller->register_routes();
	$controller = new REST\Routes\UserByEmail;
	$controller->register_routes();
	$controller = new REST\Routes\UserByID;
	$controller->register_routes();
	$controller = new REST\Routes\UserByUsername;
	$controller->register_routes();

	$controller = new REST\Routes\SiteMembers;
	$controller->register_routes();

	$controller = new REST\Routes\SiteMemberByEIN;
	$controller->register_routes();
	$controller = new REST\Routes\SiteMemberByEmail;
	$controller->register_routes();
	$controller = new REST\Routes\SiteMemberByID;
	$controller->register_routes();
	$controller = new REST\Routes\SiteMemberByUsername;
	$controller->register_routes();
}

require_once dirname(__FILE__). "/rest-api/register-fields.php";
