<?php

namespace Divi_Carousel_Lite;

use Divi_Carousel_Lite\AdminHelper;

use WP_REST_Request;
use WP_REST_Response;
use WP_Error;

use Automatic_Upgrader_Skin;
use Plugin_Upgrader;

class RestApi
{
    private static $instance;
    private $namespace = 'divi-carousel-lite/v1';

    public static function get_instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        ob_start(); // Start buffering output
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes()
    {
        register_rest_route($this->namespace, '/get_common_settings', [
            'methods'  => \WP_REST_Server::READABLE,
            'callback' => [$this, 'get_common_settings'],
            'permission_callback' => [$this, 'get_permissions_check'],
        ]);

        register_rest_route($this->namespace, '/save_common_settings', [
            'methods'  => \WP_REST_Server::EDITABLE,
            'callback' => [$this, 'save_common_settings'],
            'permission_callback' => [$this, 'get_permissions_check'],
        ]);

        register_rest_route($this->namespace, '/check_plugin_installed_and_active', [
            'methods'  => \WP_REST_Server::EDITABLE,
            'callback' => [$this, 'check_plugin_installed_and_active'],
            'permission_callback' => [$this, 'get_permissions_check'],
        ]);

        register_rest_route($this->namespace, '/activate_plugin', [
            'methods'  => \WP_REST_Server::EDITABLE,
            'callback' => [$this, 'activate_plugin'],
            'permission_callback' => '__return_true',
            'args' => array(
                'slug' => array(
                    'required' => true,
                    'validate_callback' => function ($param, $request, $key) {
                        return is_string($param);
                    }
                ),
                'plugin_file' => array(
                    'required' => true,
                    'validate_callback' => function ($param, $request, $key) {
                        return is_string($param);
                    }
                ),
            ),
        ]);

        register_rest_route($this->namespace, '/install_plugin', [
            'methods'  => \WP_REST_Server::EDITABLE,
            'callback' => [$this, 'install_plugin'],
            'permission_callback' => '__return_true',
            'args' => array(
                'slug' => array(
                    'required' => true,
                    'validate_callback' => function ($param, $request, $key) {
                        return is_string($param);
                    }
                ),
            ),
        ]);
    }

    public function edit_posts_permission()
    {
        return current_user_can('edit_posts');
    }

    public function get_permissions_check($request)
    {
        if (!current_user_can('manage_options')) {
            return new \WP_Error('rest_forbidden', esc_html__('You cannot view the templates resource.'), ['status' => $this->authorization_status_code()]);
        }
        return true;
    }

    public function authorization_status_code()
    {
        return is_user_logged_in() ? 403 : 401;
    }

    public function get_common_settings()
    {
        $options = AdminHelper::get_options();
        return $options;
    }

    public function save_common_settings(WP_REST_Request $request)
    {
        $modules = $request->get_param('modules_settings');
        update_option('_divi_carousel_lite_modules', $modules);
        return ['success' => true];
    }

    public function check_plugin_installed_and_active(WP_REST_Request $request)
    {
        $slug = $request->get_param('slug');
        $plugim_file = $request->get_param('plugin_file');

        $plugin_path = $slug . '/' . $plugim_file;

        $is_installed = file_exists(WP_PLUGIN_DIR . '/' . $plugin_path);
        $is_active = is_plugin_active($plugin_path);

        $status = [
            'installed' => $is_installed ? true : false,
            'active' => $is_active ? true : false
        ];

        return new WP_REST_Response($status, 200);
    }

    public function activate_plugin(WP_REST_Request $request)
    {

        if (!current_user_can('activate_plugins')) {
            return new WP_Error('insufficient_permissions', 'You do not have permission to activate plugins.', array('status' => 403));
        }

        $plugin_slug = $request->get_param('slug');
        $plugin_file = $request->get_param('plugin_file');

        if (!current_user_can('activate_plugins')) {
            return new WP_Error('insufficient_permissions', 'You do not have permission to activate plugins.', array('status' => 403));
        }

        $activate = activate_plugin("{$plugin_slug}/{$plugin_file}");

        if (is_wp_error($activate)) {
            return $activate;
        }

        return ['success' => true, 'message' => "Plugin activated successfully"];
    }

    public function install_plugin(WP_REST_Request $request)
    {

        if (empty($request->get_param('slug'))) {
            wp_send_json_error(
                array(
                    'slug'         => '',
                    'errorCode'    => 'no_plugin_specified',
                    'errorMessage' => __('No plugin specified.'),
                )
            );
        }

        $status = array(
            'install' => 'plugin',
            'slug'    => sanitize_key(wp_unslash($request->get_param('slug'))),
        );

        if (!current_user_can('install_plugins')) {
            $status['errorMessage'] = __('Sorry, you are not allowed to install plugins on this site.');
            wp_send_json_error($status);
        }

        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        require_once ABSPATH . 'wp-admin/includes/plugin-install.php';

        $api = plugins_api(
            'plugin_information',
            array(
                'slug'   => sanitize_key(wp_unslash($request->get_param('slug'))),
                'fields' => array(
                    'sections' => false,
                ),
            )
        );

        if (is_wp_error($api)) {
            $status['errorMessage'] = $api->get_error_message();
            wp_send_json_error($status);
        }

        $skin = new Automatic_Upgrader_Skin();
        $upgrader = new Plugin_Upgrader($skin);
        $install = $upgrader->install($api->download_link);

        if (is_wp_error($install)) {
            $status['errorMessage'] = $install->get_error_message();
            wp_send_json_error($status);
        }

        return ['success' => true, 'message' => "Plugin installed successfully"];
    }
}
