<?php
namespace WALDIR\REST\Routes;
use WALDIR\WP\WPCore as WPCore;

use WALDIR\REST\PermissionChecks\GetInstallItem as GetPermissionCheck;
use WALDIR\REST\PermissionChecks\UpdateInstallItem as UpdatePermissionCheck;

/**
 * Define the "UserByEmployeeID" REST Controller
 * *
 * @link       https://github.com/wrdsb/wordpress-plugin-waldir
 * @since      1.0.0
 *
 * @package    WALDIR
 * @subpackage WALDIR/REST/Routes
 */
class UserByEmployeeID extends WP_REST_Users_Controller {
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
		WPCore::registerRestRoute($this->apiNamespace, '/'.$this->apiVersion.'/user/employeeID/(?P<employeeID>[A-Za-z0-9-]+)', array(
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
		$permissionChecker = new GetPermissionCheck();
        return $permissionChecker($request);
    }

    /**
     * Check if a given request has access to update a specific item
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean
     */
    public function updateItemPermissionsCheck(WP_REST_Request $request): bool {
		$permissionChecker = new UpdatePermissionCheck();
        return $permissionChecker($request);
    }

	/**
	 * Get the user, if the EmployeeID is valid.
	 *
	 * @param int $employeeID Supplied EmployeeID.
	 * @return WP_User|WP_Error User, if user is found, WP_Error otherwise.
	 */
	protected function getItem($employeeID) {
		$malformedError = new WP_Error( 'rest_user_invalid_id', __( 'Invalid EmployeeID.' ), array( 'status' => 404 ) );
		$notFoundError = new WP_Error( 'rest_user_invalid_id', __( 'EmployeeID not found.' ), array( 'status' => 404 ) );

		if ( (int) $employeeID <= 0 ) {
			return $malformedError;
		}

		// Query for users based on the meta data
		$user_query = new WP_User_Query(
			array(
				'blog_id'     => 1,
				'meta_key'    => 'waldirEmployeeID',
				'meta_value'  => $employeeID,
			)
		);
 
		// Get the results from the query, returning the first user
		$users = $user_query->get_results();
		$user = reset($users);

		if ( empty( $user ) || ! $user->exists() ) {
			return $notFoundError;
		}

		return $user;
	}

	/**
	 * Updates a single user.
	 *
	 * @access public
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function updateItem( $request ) {
		$user = $this->get_user( $request['id'] );

		if ( is_wp_error( $user ) ) {
			return $user;
		}

		if ( ! $user->ID ) {
			return new WP_Error( 'rest_user_invalid_id', __( 'Invalid user ID.' ), array( 'status' => 404 ) );
		}

		if ( empty( $request['roles'] ) ) {
			return new WP_Error( 'rest_user_missing_role', __( 'Bad Request: missing role' ), array( 'status' => 400 ) );
		}

		// Protect existing roles
		if ( is_user_member_of_blog( $user->ID, get_current_blog_id() ) ) {
			$member = new WP_User( $user->ID, '', get_current_blog_id() );

			if ( in_array( 'administrator', $member->roles) ) {
				return new WP_Error( 'rest_user_demotion_failed', __( 'Bad Request: User is an administrator' ), array( 'status' => 400 ) );
			}
			if ( in_array( 'editor', $member->roles) ) {
				return new WP_Error( 'rest_user_demotion_failed', __( 'Bad Request: User is an editor' ), array( 'status' => 400 ) );
			}
			if ( in_array( 'author', $member->roles) ) {
				return new WP_Error( 'rest_user_demotion_failed', __( 'Bad Request: User is an author' ), array( 'status' => 400 ) );
			}
		}

		$user_id = $user->ID;
		$blog_id = get_current_blog_id();
		$role = (string) reset( $request['roles'] );

		$result = add_user_to_blog( $blog_id, $user_id, $role );

		if ( is_wp_error( $result ) ) {
			return new WP_Error( 'rest_user_add_failure', $result->get_error_message(), array( 'status' => 500 ) );
		}

		$request->set_param( 'context', 'edit' );

		$response = $this->prepare_item_for_response( $user, $request );
		$response = rest_ensure_response( $response );

		return $response;
	}
}

