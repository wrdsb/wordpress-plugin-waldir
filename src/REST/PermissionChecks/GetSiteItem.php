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
abstract class GetSiteItem {
    /**
     * Check if a given request has access to read an item or a collection of items
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean
     */
    public static function permissionsCheck(WP_REST_Request $request): bool {
        // because this only makes sense in a multisite install:
		self::multisiteCheck();

        if (WPCore::currentUserCan('setup_network')) {
            return true;
        }

        $user = WPCore::getCurrentUser();
        if (empty($user)) return false;

        $siteID = self::getSiteID($request);
        if ($siteID === '') return false;
        
        WPCore::switchToBlog($siteID);
		
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
	protected static function multisiteCheck() {
		$error = new WP_Error( 'rest_multisite_check_failure', __( 'Bad Request: not a multisite install' ), array( 'status' => 400 ) );

        if ( ! WPCore::isMultisite() ) {
			return $error;
		}

		return true;
	}

    /**
     * Given a WP_REST_Request object, retrieve the corresponding site id.
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return string The form ID from the request.
     */
    private static function getSiteID(WP_REST_Request $request): string {
        $params = $request->get_url_params();
        $siteID = $params['site'] ? $params['site'] : '';

        return $siteID;
    }
}