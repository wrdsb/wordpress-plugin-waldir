<?php
/*
* Plugin Name: WALDIR
* Plugin URI: https://github.com/wrdsb/wordpress-plugin-waldir
* Description: Custom meta fields and API endpoints for Sites and Users.
* Author: WRDSB
* Author URI: https://github.com/wrdsb
* Version: 1.0.0
* License: GPLv3 or later
* Text Domain: waldir
*/

require_once dirname(__FILE__). "/rest-api/register-routes.php";
require_once dirname(__FILE__). "/rest-api/register-fields.php";
