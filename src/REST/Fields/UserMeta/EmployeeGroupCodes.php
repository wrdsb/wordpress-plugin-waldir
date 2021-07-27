<?php
namespace WALDIR\REST\Fields\UserMeta;
use WALDIR\WP\WPCore;

/**
 * Define the "waldirEmployeeGroupCodes" user meta field.
 * *
 * @link       https://github.com/wrdsb/wordpress-plugin-waldir
 * @since      1.0.0
 *
 * @package    WALDIR
 * @subpackage WALDIR/REST/Fields
 */
class EmployeeGroupCodes {
    public static function registerField() {
        WPCore::registerRESTField(
			'user',
			'waldirEmployeeGroupCodes',
			array(
				'get_callback'    => array(WALDIR\REST\Fields\Callbacks::class, 'usermetaGetValue'),
				'update_callback' => array(WALDIR\REST\Fields\Callbacks::class, 'usermetaUpdateValue'),
				'schema'          => null,
			)
		);
	}
}
