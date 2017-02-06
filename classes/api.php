<?php
class Example_Plugin_API {

    /** @var string */
    protected $namespace;

    /** @var string */
    protected $version;

    /**
     * Example_Plugin_API constructor.
     *
     * @param string $namespace
     * @param string $version
     */
    public function __construct( $namespace, $version ){
        $this->namespace = $namespace;
        $this->version = $version;
    }

    /**
     *
     */
    public function register_routes(){
        register_rest_route( $this->namespace . '/' . $this->version, '/settings', array(
                array(
                    'methods'         => \WP_REST_Server::READABLE,
                    'callback'        => array( $this, 'get_settings' ),
                    'args'            => array(

                    ),
                    'permission_callback' => array( $this, 'permissions_check' )
                ),
                array(
                    'methods'         => \WP_REST_Server::CREATABLE,
                    'callback'        => array( $this, 'update_settings' ),
                    'args'            => array(
                        'api_key' => array(
                            'required' => true,
                            'default' => '',
                            'type' => 'string',
                            'sanitize_callback' => array( $this, 'strip_tags' )
                        ),
                        'enabled' => array(
                            'required' => true,
                            'default' => 'false',
                            'type' => 'boolean'
                        )
                    ),
                    'permission_callback' => array( $this, 'permissions_check' )
                ),
            )
        );
    }

    public function permissions_check(  ){
        return current_user_can( 'manage_options' );
    }

    public function get_settings( \WP_REST_Request $request ){
        return rest_ensure_response( Example_Plugin_Settings::get_settings() );

    }

    public function update_settings( \WP_REST_Request $request ){
        Example_Plugin_Settings::write_settings( $request[ 'api_key' ], $request[ 'enabled' ] );
        return rest_ensure_response( Example_Plugin_Settings::get_settings() );
    }

    public function strip_tags( $value ){
        if(  is_string( $value ) ){
            return strip_tags( $value );

        }

        return '';
    }

}