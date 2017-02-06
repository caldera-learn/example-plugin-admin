<?php
class Example_Plugin_Settings{

    /**
     * Get settings
     *
     * @return array
     */
    public static function get_settings(){
        $api_key = get_option( '_example_api_key', 'hi roy' );
        if( ! is_string( $api_key ) ){
            $api_key = '';
        }else{
            $api_key = strip_tags( $api_key );
        }

        $enabled = get_option( '_example_enabled', false );
        if( ! filter_var( $enabled, FILTER_VALIDATE_BOOLEAN ) ){
            $enabled = false;
        }

        return array(
            'api_key' => $api_key,
            'enabled' => $enabled
        );
    }

    /**
     * Write settings
     *
     * @param string $api_key
     * @param bool $enabled
     */
    public static  function write_settings( $api_key = '', $enabled = false ){
        update_option( '_example_api_key', $api_key );
        update_option( '_example_enabled', $enabled );
    }
}