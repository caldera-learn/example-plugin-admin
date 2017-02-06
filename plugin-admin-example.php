<?php
/**
Plugin Name: Plugin Example Dashboard

 */


add_action('init', function () {
    include __DIR__ . '/classes/settings.php';
    include __DIR__ . '/classes/api.php';
    include __DIR__ . '/classes/page.php';

    if (is_admin()) {
        new Example_Plugin_Page(
            plugin_dir_path(__FILE__),
            plugin_dir_url(__FILE__),
            'example-plugin'
        );
    }

});

add_action('rest_api_init', function () {
    $api = new Example_Plugin_API('example', 'v1');
    $api->register_routes();
});

