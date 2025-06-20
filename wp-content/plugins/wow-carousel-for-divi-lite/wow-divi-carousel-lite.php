<?php
/*
Plugin Name: Divi Carousel Maker
Plugin URI:  https://diviepic.com/
Description: Carousel Maker the most powerful and user-friendly Divi Carousel plugin to create beautiful carousels with any modules.
Version:     2.1.3
Author:      PlugPress
Author URI:  https://plugpress.co
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: divi-carousel-lite
Domain Path: /languages
*/

if (!defined('ABSPATH')) {
    exit;
}

define('DCM_PLUGIN_VERSION', '2.1.3');
define('DCM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DCM_PLUGIN_URL', plugin_dir_url(__FILE__));
define('DCM_PLUGIN_ASSETS', trailingslashit(DCM_PLUGIN_URL . 'assets'));
define('DCM_PLUGIN_FILE', __FILE__);
define('DCM_PLUGIN_BASE', plugin_basename(__FILE__));

if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    return;
}

require_once __DIR__ . '/vendor/autoload.php';

function dcl_is_pro_installed()
{
    return defined('DIVI_CAROUSEL_PRO_VERSION');
}

function dcl_is_dm_pro_installed()
{
    return defined('DIVI_CAROUSEL_PRO_VERSION') && 'wow-divi-carousel' === DIVI_CAROUSEL_PRO_BASE;
}

function divi_carousel_maker_library()
{
    $layouts = array(
        '-1' => esc_html__(' --Select a Slide-- ', 'divi-carousel-lite')
    );

    $saved_layouts = get_posts(array(
        'post_type'      => 'et_pb_layout',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'title',
    ));

    if (!empty($saved_layouts)) {
        $layout_options = wp_list_pluck($saved_layouts, 'post_title', 'ID');
        $layouts = array_merge($layouts, $layout_options);
    }

    return $layouts;
}

function dcm_global_assets_list($global_list)
{

    $assets_list   = array();
    $assets_prefix = et_get_dynamic_assets_path();

    $assets_list['et_icons_fa'] = array(
        'css' => "{$assets_prefix}/css/icons_fa_all.css",
    );

    return array_merge($global_list, $assets_list);
}

function dcm_inject_fa_icons($icon_data)
{
    if (function_exists('et_pb_maybe_fa_font_icon') && et_pb_maybe_fa_font_icon($icon_data)) {
        add_filter('et_global_assets_list', 'dcm_global_assets_list');
        add_filter('et_late_global_assets_list', 'dcm_global_assets_list');
    }
}

require_once 'plugin-loader.php';
