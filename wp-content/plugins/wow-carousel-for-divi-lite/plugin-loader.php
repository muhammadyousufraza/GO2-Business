<?php

namespace Divi_Carousel_Lite;

use Divi_Carousel_Lite\Assets_Manager;
use Divi_Carousel_Lite\RestApi;
use Divi_Carousel_Lite\Dashboard;
use Divi_Carousel_Lite\ModulesManager;

/**
 * Main class plugin
 */
class Plugin_Loader
{
    /**
     * @var Plugin_Loader
     */
    private static $instance;

    const PLUGIN_PATH   = DCM_PLUGIN_DIR;
    const BASENAME      = DCM_PLUGIN_BASE;

    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct()
    {
        register_activation_hook(DCM_PLUGIN_FILE, array($this, 'activation'));
        add_action('plugins_loaded', array($this, 'hooks_init'));
        add_action('init', array($this, 'load_textdomain'));
    }

    public function activation()
    {
        update_option('divi_carousel_lite_version', DCM_PLUGIN_VERSION);

        if (!get_option('divi_carousel_lite_activation_time')) {
            update_option('divi_carousel_lite_activation_time', time());
        }

        if (!get_option('divi_carousel_lite_install_date')) {
            update_option('divi_carousel_lite_install_date', time());
        }

        self::init();
    }

    public function hooks_init()
    {
        add_action('divi_extensions_init', array($this, 'init_extension'));

        Assets_Manager::get_instance();
        RestApi::get_instance();
        Dashboard::get_instance();
    }

    public function load_textdomain()
    {
        load_plugin_textdomain('divi-carousel-lite', false, self::BASENAME . '/languages');
    }

    public static function init()
    {
        $module_status = get_option('_divi_carousel_lite_modules', array());
        $modules = AdminHelper::get_modules();

        if (empty($module_status)) {
            foreach ($modules as $module) {
                $module_status[$module] = $module;
            }

            update_option('_divi_carousel_lite_modules', $module_status);
        }
    }

    public function init_extension()
    {
        ModulesManager::get_instance();
    }
}

Plugin_Loader::get_instance();
