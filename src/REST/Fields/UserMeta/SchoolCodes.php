<?php
namespace WALDIR\REST\Fields\UserMeta;
use WALDIR\WP\WPCore;

/**
 * Define the "waldirSchoolCodes" user meta field.
 * *
 * @link       https://github.com/wrdsb/wordpress-plugin-waldir
 * @since      1.0.0
 *
 * @package    WALDIR
 * @subpackage WALDIR/REST/Fields
 */
abstract class SchoolCodes {
    public static function registerField() {
        WPCore::registerRESTField(
			'user',
			'waldirSchoolCodes',
			array(
				'get_callback'    => array(WALDIR\REST\Fields\Callbacks::class, 'usermetaGetValue'),
				'update_callback' => array(WALDIR\REST\Fields\Callbacks::class, 'usermetaUpdateValue'),
				'schema'          => null,
			)
		);
	}
}
