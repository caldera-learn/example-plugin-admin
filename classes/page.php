<?php
class Example_Plugin_Page {

    /** @var  string */
    protected $dir;

    /** @var  string */
    protected $url;

    /** @var  string */
    protected $menu_slug;

    /**
     * Example_Plugin_Page constructor.
     * @param string $dir
     * @param string $url
     * @param string $menu_slug
     */
    public function __construct( $dir, $url, $menu_slug ) {
        $this->dir = $dir;
        $this->url = $url;
        $this->menu_slug = $menu_slug;
        add_action( 'admin_menu', array( $this, 'add_page' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
        add_action( 'example_plugin_app_html_after', array( $this, 'templates' ) );
    }

    /**
     * Add menu page
     *
     * @since 0.0.1
     *
     * @uses "admin_menu"
     */
    public function add_page(){
        add_menu_page(
            __( 'Fancy Dashboard', 'text-domain' ),
            __( 'Fancy Dashboard', 'text-domain' ),
            'manage_options',
            $this->menu_slug,
            array( $this, 'render_admin' ) );
    }

    /**
     * Load scripts for admin page
     *
     * @since 0.0.1
     *
     * @uses "admin_enqueue_scripts"
     *
     * @param $hook
     */
    public function scripts( $hook ){
        if( $hook === 'toplevel_page_' . $this->menu_slug ){
            wp_register_script( 'bootstrap', '//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js' );
            wp_register_script( 'angularjs-resource', '//cdnjs.cloudflare.com/ajax/libs/angular-resource/1.6.1/angular-resource.min.js' );
            wp_register_script( 'angularjs-ui-router', '//cdnjs.cloudflare.com/ajax/libs/angular-ui-router/0.4.2/angular-ui-router.min.js' );
            wp_register_script( 'angularjs-sanitize', '//cdnjs.cloudflare.com/ajax/libs/angular-sanitize/1.6.1/angular-sanitize.min.js' );
            wp_register_script( 'angularjs', '//cdnjs.cloudflare.com/ajax/libs/angular.js/1.6.1/angular.min.js' );

            wp_enqueue_script( $this->menu_slug, $this->url . '/js/app.js', array(
                'jquery',
                'bootstrap',
                'angularjs',
                'angularjs-resource',
                'angularjs-sanitize',
                'angularjs-ui-router'
            ));

            wp_localize_script( $this->menu_slug, 'EXAMPLE', array(
                'api' => array(
                    'settings' => esc_url_raw( rest_url( 'example/v1/settings' ) ),
                    'nonce' => wp_create_nonce( 'wp_rest' )
                ),
                'strings' => array(
                    'saved' => esc_html__( 'Settings Saved', 'text-domain' ),
                    'error' => esc_html__( 'Error: Settings Not Saved', 'text-domain' ),
                )
            ));

            wp_enqueue_style( 'bootstrap', '//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css' );
        }
    }

    /**
     * Render plugin admin page
     *
     * @since 0.0.1
     *
     * @todo move to partial and/or load via AJAX
     */
    public function render_admin(){

        ?>
        <div id="ng-app" ng-app="example">
            <h1 class="nav-tab-wrapper">
                <a ui-sref="settings" class="nav-tab nav-tab-active">Settings</a>
                <a ui-sref="docs" class="nav-tab nav-tab-active">Docs</a>
            </h1>
            <div id="app-content">
                <div ui-view></div>
            </div>
            <?php do_action( 'example_plugin_app_html_bottom' ); ?>
        </div>

        <?php
        do_action( 'example_plugin_app_html_after' );
    }

    public function templates(){
        echo include  $this->dir . '/templates/settings.php';
        echo include  $this->dir . '/templates/add-ons.php';
    }

}