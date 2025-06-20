<?php

namespace Divi_Carousel_Lite;

use Divi_Carousel_Lite\Backend_Helpers;

class Assets_Manager
{

    private static $instance = null;

    public static function get_instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_scripts']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_builder_scripts']);
    }

    public function enqueue_assets($prefix, $dependencies = ['react-dom', 'react'], $include_style = true, $include_script = true)
    {
        $manifest = $this->get_manifest();

        if ($manifest) {
            $this->enqueue_from_manifest($prefix, $dependencies, $include_style, $include_script, $manifest);
        } else {
            $this->enqueue_fallback($prefix, $dependencies, $include_style, $include_script);
        }
    }

    private function get_manifest()
    {
        $manifest_path = DCM_PLUGIN_DIR . 'assets/mix-manifest.json';

        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);

        $manifest = json_decode(@file_get_contents($manifest_path, false, $context), true);

        return is_array($manifest) ? $manifest : null;
    }

    private function enqueue_from_manifest($prefix, $dependencies, $include_style, $include_script, $manifest)
    {
        if ($include_script) {
            wp_enqueue_script(
                "dcl-{$prefix}",
                DCM_PLUGIN_ASSETS . $manifest["/js/{$prefix}.js"],
                $dependencies,
                DCM_PLUGIN_VERSION,
                true
            );
        }

        if ($include_style && isset($manifest["/css/{$prefix}.css"])) {
            wp_enqueue_style(
                "dcl-{$prefix}",
                DCM_PLUGIN_ASSETS . $manifest["/css/{$prefix}.css"],
                [],
                DCM_PLUGIN_VERSION
            );
        }
    }

    private function enqueue_fallback($prefix, $dependencies, $include_style, $include_script)
    {
        if ($include_script) {
            wp_enqueue_script(
                "dcl-{$prefix}",
                DCM_PLUGIN_ASSETS . "js/{$prefix}.js",
                $dependencies,
                DCM_PLUGIN_VERSION,
                true
            );
        }

        if ($include_style) {
            $style_path = DCM_PLUGIN_ASSETS . "css/{$prefix}.css";
            if (file_exists($style_path)) {
                wp_enqueue_style(
                    "dcl-{$prefix}",
                    $style_path,
                    [],
                    DCM_PLUGIN_VERSION
                );
            }
        }
    }

    public function enqueue_frontend_scripts()
    {
        $this->enqueue_libraries();
        $this->enqueue_assets('frontend');
    }

    private function enqueue_libraries()
    {
        wp_enqueue_script('dcl-slick', DCM_PLUGIN_ASSETS . 'libs/slick/slick.min.js', ['jquery'], DCM_PLUGIN_VERSION, true);
        wp_enqueue_script('dcl-magnific', DCM_PLUGIN_ASSETS . 'libs/magnific/magnific-popup.min.js', ['jquery'], DCM_PLUGIN_VERSION, true);

        wp_enqueue_style('dcl-slick', DCM_PLUGIN_ASSETS . 'libs/slick/slick.min.css', [], DCM_PLUGIN_VERSION);
        wp_enqueue_style('dcl-magnific', DCM_PLUGIN_ASSETS . 'libs/magnific/magnific-popup.min.css', [], DCM_PLUGIN_VERSION);
    }

    public function enqueue_builder_scripts()
    {
        if (function_exists('et_core_is_fb_enabled') && et_core_is_fb_enabled()) {
            $this->enqueue_assets('builder', ['react-dom', 'react'], false, true);
        }
    }

    // public function load_backend_data()
    // {
    //     if (!function_exists('et_fb_process_shortcode') || !class_exists(Backend_Helpers::class)) {
    //         return;
    //     }

    //     $helpers = new Backend_Helpers();

    //     add_filter('et_fb_backend_helpers', [$helpers, 'static_asset_helpers'], 11);
    //     add_filter('et_fb_get_asset_helpers', [$helpers, 'asset_helpers'], 11);

    //     $enqueue_scripts_callback = function () use ($helpers) {
    //         wp_localize_script('et-frontend-builder', 'DCLBuilderBackend', $helpers->static_asset_helpers());
    //     };

    //     add_action('wp_enqueue_scripts', $enqueue_scripts_callback);
    //     add_action('admin_enqueue_scripts', $enqueue_scripts_callback);
    // }
}
