<?php
namespace WALDIR\REST\PermissionChecks;
use WALDIR\WP\WPCore as WPCore;

/**
 * Define the "GetSiteItemPermissionsCheck" class
 * *
 * @link       https://github.com/wrdsb/wordpress-plugin-waldir
 * @since      1.0.0
 *
 * @package    WALDIR
 * @subpackage WALDIR/REST/
 */
class GetSiteItem {
    /**
     * Check if a given request has access to read an item or a collection of items
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean
     */
    public function permissionsCheck(WP_REST_Request $request): bool {
        // because this only makes sense in a multisite install:
		$this->multisite_check();

        if (WPCore::currentUserCan('setup_network')) {
            return true;
        }

        $user = WPCore::getCurrentUser();
        if (empty($user)) return false;

        $blogID = $this->getBlogID($request);
        if ($blogID === '') return false;
        
        WPCore::switchToBlog($blogID);
		
        if (WPCore::userCan($user->id, 'manage_options')) {
            WPCore::restoreCurrentBlog();
            return true;
        }

        WPCore::restoreCurrentBlog();
        return false;
    }

	/**
	 * Confirm we're running multisite
	 *
	 * @param int $id Supplied ID.
	 * @return WP_User|WP_Error True if ID is valid, WP_Error otherwise.
	 */
	protected function multisiteCheck() {
		$error = new WP_Error( 'rest_multisite_check_failure', __( 'Bad Request: not a multisite install' ), array( 'status' => 400 ) );

		if ( !is_multisite() ) {
			return $error;
		}

		return true;
	}
}