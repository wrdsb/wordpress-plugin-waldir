<?php
namespace WALDIR\REST\PermissionChecks;
use WALDIR\WP\WPCore as WPCore;

/**
 * Define the "UpdateInstallItemPermissionsCheck" class
 * *
 * @link       https://github.com/wrdsb/wordpress-plugin-waldir
 * @since      1.0.0
 *
 * @package    WALDIR
 * @subpackage WALDIR/REST/
 */
abstract class UpdateInstallItem {
    /**
     * Check if a given request has access to update a specific item
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean
     */
    public static function permissionsCheck(WP_REST_Request $request): bool {
        $user = WPCore::getCurrentUser();
        if (empty($user)) return false;

        if ( ! WPCore::isMultisite() ) {
            if (WPCore::userCan($user->id, 'manage_options')) {
                WPCore::restoreCurrentBlog();
                return true;
            }

        } else {
            if (WPCore::currentUserCan('setup_network')) {
                return true;
            }
   
            WPCore::switchToBlog(1);
            
            if (WPCore::userCan($user->id, 'manage_options')) {
                WPCore::restoreCurrentBlog();
                return true;
            }
    
            WPCore::restoreCurrentBlog();
            return false;
        }
    }
}