<?php

namespace Divi_Carousel_Lite;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
class Plugin_Upgrader
{
    protected $package;
    protected $version;
    protected $plugin_name;
    protected $plugin_slug;

    public function __construct($args = array())
    {
        foreach ($args as $key => $value) {
            $this->$key = $value;
        }
    }

    private function print_inline_style()
    {

?>
        <style>
            .wrap {
                overflow: hidden;
                max-width: 860px;
                margin: auto;
            }

            h1 {
                background: #0e40ff;
                text-align: center;
                color: #fff !important;
                padding: 80px !important;
                text-transform: uppercase;
                letter-spacing: 1px;
            }
        </style>
<?php
    }

    public function apply_package()
    {
        $update_plugins = get_site_transient('update_plugins');

        if (!is_object($update_plugins)) {
            $update_plugins = new \stdClass();
        }

        $plugin_info = new \stdClass();
        $plugin_info->new_version = $this->version;
        $plugin_info->slug = $this->plugin_slug;
        $plugin_info->package = $this->package;
        $plugin_info->url = 'https://diviepic.com/';

        $update_plugins->response[$this->plugin_name] = $plugin_info;

        set_site_transient('update_plugins', $update_plugins);
    }

    protected function upgrade()
    {
        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

        $upgrader_args = array(
            'url'    => 'update.php?action=upgrade-plugin&plugin=' . urlencode($this->plugin_name),
            'plugin' => $this->plugin_name,
            'nonce'  => 'upgrade-plugin_' . $this->plugin_name,
            'title' => __('Divi Carousel Lite <p>Rollback to Previous Version</p>', 'divi-carousel-lite'),
        );

        $this->print_inline_style();

        $upgrader_skin = new \Plugin_Upgrader_Skin($upgrader_args);
        $upgrader      = new \Plugin_Upgrader($upgrader_skin);
        $upgrader->upgrade($this->plugin_name);
    }

    public function run()
    {
        $this->apply_package();
        $this->upgrade();
    }
}
