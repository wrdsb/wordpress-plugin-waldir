<?php
namespace WALDIR\REST\Routes;
use WALDIR\WP\WPCore as WPCore;

use WALDIR\REST\PermissionChecks\GetInstallItem as GetPermissionCheck;
use WALDIR\REST\PermissionChecks\UpdateInstallItem as UpdatePermissionCheck;

/**
 * Define the "UserByUsername" REST Controller
 * *
 * @link       https://github.com/wrdsb/wordpress-plugin-waldir
 * @since      1.0.0
 *
 * @package    WALDIR
 * @subpackage WALDIR/REST/Routes
 */
class UserByUsername extends WP_REST_Users_Controller {
    private $apiNamespace;
    private $apiVersion;

	/**
	 * Instance of a user meta fields object.
	 *
	 * @access protected
	 * @var WP_REST_User_Meta_Fields
	 */
	protected $meta;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->apiNamespace = 'waldir';
		$this->apiVersion = 'v1';

		$this->meta = new WP_REST_User_Meta_Fields();
	}

	/**
	 * Register the routes for the objects of the controller.
	 */
	public function registerRoutes() {
		WPCore::registerRestRoute($this->apiNamespace, '/'.$this->apiVersion.'/user/username/(?P<username>[A-Za-z0-9-]+)', array(
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array( $this, 'getItem' ),
                'permission_callback' => array( $this, 'getItemPermissionsCheck' ),
				'args'                => array(
					'context' => $this->get_context_param( array( 'default' => 'edit' ) ),
				),
            ),
            array(
                'methods'             => WP_REST_Server::EDITABLE,
                'callback'            => array( $this, 'updateItem' ),
                'permission_callback' => array( $this, 'updateItemPermissionsCheck' ),
            ),
		));
	}

    /**
     * Check if a given request has access to read an item
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean
     */
    public function getItemPermissionsCheck(WP_REST_Request $request): bool {
        return GetPermissionCheck::permissionsCheck($request);
    }

    /**
     * Check if a given request has access to update a specific item
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean
     */
    public function updateItemPermissionsCheck(WP_REST_Request $request): bool {
		return UpdatePermissionCheck::permissionsCheck($request);
    }

	/**
	 * Get the user, if the username is valid.
	 *
	 * @param int $username Supplied username address.
	 * @return WP_User|WP_Error User, if user is found, WP_Error otherwise.
	 */
	protected function getItem($username) {
		$notFoundError = new WP_Error( 'rest_user_invalid_id', __( 'Username not found.' ), array( 'status' => 404 ) );

		$user = WPCore::getUserBy('login', $username);

		if ( !$user || empty($user) || !$user->exists() ) {
			return $notFoundError;
		}

		return $user;
	}
}

