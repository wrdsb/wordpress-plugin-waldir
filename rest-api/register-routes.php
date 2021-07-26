<?php
if ( ! class_exists( 'WALDIR_REST_Users_Controller' ) ) {
	require_once dirname( __FILE__ ) . '/routes/sites-controller.php';
	require_once dirname( __FILE__ ) . '/routes/site-controller.php';

	require_once dirname( __FILE__ ) . '/routes/install-user-by-id-controller.php';
	require_once dirname( __FILE__ ) . '/routes/install-user-by-email-controller.php';
	require_once dirname( __FILE__ ) . '/routes/install-user-by-username-controller.php';
	require_once dirname( __FILE__ ) . '/routes/install-user-by-ein-controller.php';

	require_once dirname( __FILE__ ) . '/routes/site-user-by-id-controller.php';
	require_once dirname( __FILE__ ) . '/routes/site-user-by-email-controller.php';
	require_once dirname( __FILE__ ) . '/routes/site-user-by-username-controller.php';
	require_once dirname( __FILE__ ) . '/routes/site-user-by-ein-controller.php';
}

add_action( 'rest_api_init', 'create_waldir_rest_routes', 99 );
function create_waldir_rest_routes() {
	// Sites
	$controller = new WALDIR_Sites_Controller;
	$controller->register_routes();
	$controller = new WALDIR_Site_Controller;
	$controller->register_routes();

	// Users
	$controller = new WALDIR_Install_Users;
	$controller->register_routes();

	$controller = new WALDIR_Install_User_by_ID_Controller;
	$controller->register_routes();
	$controller = new WALDIR_Install_User_by_Email_Controller;
	$controller->register_routes();
	$controller = new WALDIR_Install_User_by_Username_Controller;
	$controller->register_routes();
	$controller = new WALDIR_Install_User_by_EIN_Controller;
	$controller->register_routes();

	$controller = new WALDIR_Site_Members;
	$controller->register_routes();

	$controller = new WALDIR_Site_Member_by_ID_Controller;
	$controller->register_routes();
	$controller = new WALDIR_Site_Member_by_Email_Controller;
	$controller->register_routes();
	$controller = new WALDIR_Site_Member_by_Username_Controller;
	$controller->register_routes();
	$controller = new WALDIR_Site_Member_by_EIN_Controller;
	$controller->register_routes();
}

