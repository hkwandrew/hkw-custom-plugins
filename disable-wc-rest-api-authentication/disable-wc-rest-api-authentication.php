<?php
/*
Plugin Name: Disable WooCommerce REST API Authentication
Description: Override WooCommerce capability check so that all REST API queries are allowed. Intended only for development environments.
Author: HKW
Author URI: https://hkw.io/
Version: 0.1.0
Tested up to: 6.5.2
PHP Version: 8.1.23
Text Domain: disable-wc-rest-api-auth
*/

class HKW_Disable_WC_Rest_Api {

    public function __construct() {
        add_filter( 'woocommerce_rest_check_permissions', [ $this, 'allow_rest_api_queries' ], 10, 4 );
        add_filter( 'rest_post_dispatch', [ $this, 'log_rest_requests' ], 10, 3 );
        add_filter( 'determine_current_user', [ $this, 'auto_authenticate' ] );
        add_action( 'rest_api_init', [ $this, 'disable_rest_caching' ] );
        register_activation_hook( __FILE__, [ $this, 'environment_check' ] );
    }

    /**
     * Allow REST API queries by overriding WooCommerce capability checks.
     *
     * @param bool $permission Current permission.
     * @param string $context Request context.
     * @param int $zero Unused parameter.
     * @param mixed $object Main object being manipulated.
     * @return bool True to allow all queries.
     */
    public function allow_rest_api_queries( $permission, $context, $zero, $object ) {
        if ( 'read' !== $context ) {
            return $permission;
        }

        error_log( sprintf( 'Permission: %s, Context: %s; Object: %s', var_export( $permission, true ), $context, var_export( $object, true ) ) );

        return true;
    }

    /**
     * Log REST requests for debugging purposes.
     *
     * @param WP_REST_Response $response REST response.
     * @param WP_REST_Server $server REST server instance.
     * @param WP_REST_Request $request REST request.
     * @return WP_REST_Response Modified REST response.
     */
    public function log_rest_requests( $response, $server, $request ) {
        $log_entry = sprintf(
            "REST Request: %s\nREST Response: %s\n",
            json_encode( [ 'path' => $request->get_route(), 'params' => $request->get_params() ], JSON_PRETTY_PRINT ),
            json_encode( [ 'data' => $response->get_data(), 'status' => $response->get_status() ], JSON_PRETTY_PRINT )
        );

        error_log( $log_entry );

        return $response;
    }

    /**
     * Automatically authenticate users.
     *
     * @param int $user_id Current user ID.
     * @return int Modified user ID.
     */
    public function auto_authenticate( $user_id ) {
        return 1; // Or another user ID.
    }

    /**
     * Disable REST API caching.
     */
    public function disable_rest_caching() {
        header( 'Cache-Control: no-cache, must-revalidate, max-age=0' );
    }

    /**
     * Check if the plugin is used in a development environment.
     */
    public function environment_check() {
        if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
            deactivate_plugins( plugin_basename( __FILE__ ) );
            wp_die( __('This plugin has been deactivated because it is intended only for development environments.', 'disable-wc-rest-api-auth') );
        }
    }
}

new HKW_Disable_WC_Rest_Api();
