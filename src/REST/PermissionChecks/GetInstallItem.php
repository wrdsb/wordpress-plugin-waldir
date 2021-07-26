<?php
namespace WALDIR\REST\PermissionChecks;
use WRDSB\Staff\Modules\WP\WPCore as WPCore;

/**
 * Define the "GetInstallItemPermissionsCheck" class
 * *
 * @link       https://github.com/wrdsb/wordpress-plugin-waldir
 * @since      1.0.0
 *
 * @package    WALDIR
 * @subpackage WALDIR/REST/
 */
class GetInstallItem {
    /**
     * Check if a given request has access to read an item or a collection of items
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean
     */
    public function permissionsCheck(WP_REST_Request $request): bool {
        $user = WPCore::getCurrentUser();
        if (empty($user)) return false;

        if ( !is_multisite() ) {
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