<?php
namespace WALDIR\REST\Fields;
use WALDIR\WP\WPCore;

abstract class Callbacks {
    /**
     * Handler for getting custom user meta field data.
     *
     * @param array $object The object from the response
     * @param string $field_name Name of field
     * @param WP_REST_Request $request Current request
     *
     * @return mixed
     */
    public static function usermetaGetValue( $object, $field_name, $request ) {
        return WPCore::getUserMeta( $object['id'], $field_name );
    }

    /**
     * Handler for getting custom user option field data.
     *
     * @param array $object The object from the response
     * @param string $field_name Name of field
     * @param WP_REST_Request $request Current request
     *
     * @return mixed
     */
    public static function useroptionGetValue( $object, $field_name, $request ) {
        return WPCore::getUserOption( $field_name, $object['id'] );
    }

    /**
     * Handler for updating custom user meta field data.
     *
     * @param mixed $value The value of the field
     * @param object $object The object from the response
     * @param string $field_name Name of field
     *
     * @return bool|int
     */
    public static function usermetaUpdateValue( $value, $object, $field_name ) {
        if ( ! $value || ! is_string( $value ) ) {
            return;
        }
        return WPCore::updateUserMeta( $object->ID, $field_name, strip_tags( $value ) );
    }

    /**
     * Handler for updating custom user option field data.
     *
     * @param mixed $value The value of the field
     * @param object $object The object from the response
     * @param string $field_name Name of field
     *
     * @return bool|int
     */
    public static function useroptionUpdateValue( $value, $object, $field_name ) {
        if ( ! $value || ! is_string( $value ) ) {
            return;
        }
        return WPCore::updateUserOption( $object->ID, $field_name, strip_tags( $value ) );
    }
}