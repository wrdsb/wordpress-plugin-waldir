<?php
namespace WALDIR\REST\Fields\UserOptions;
use WALDIR\WP\WPCore;

/**
 * Define the "waldirContactOptions" user option field.
 * *
 * @link       https://github.com/wrdsb/wordpress-plugin-waldir
 * @since      1.0.0
 *
 * @package    WALDIR
 * @subpackage WALDIR/REST/Fields
 */
class ContactOptions {
    public static function registerField() {
        WPCore::registerRESTField(
            'user',
            'waldirContactOptions',
            array(
                'get_callback'    => array(WALDIR\REST\Fields\Callbacks::class, 'useroptionGetValue'),
                'update_callback' => array(WALDIR\REST\Fields\Callbacks::class, 'useroptionUpdateValue'),
                'schema'          => null,
            )
        );
    }
}
