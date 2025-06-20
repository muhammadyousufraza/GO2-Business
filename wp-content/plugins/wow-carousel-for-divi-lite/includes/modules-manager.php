<?php

namespace Divi_Carousel_Lite;

use Divi_Carousel_Lite\AdminHelper;

class ModulesManager
{
    private static $instance;

    private $modules_lite = [
        [
            'name' => 'carousel-maker',
            'title' => 'Carousel Maker',
            'icon' => '',
            'child_name' => 'carousel-maker-child',
            'demo_link' => '',

        ],
        [
            'name' => 'image-carousel',
            'title' => 'Image Carousel',
            'icon' => '',
            'child_name' => 'image-carousel-child',
            'demo_link' => '',

        ],
        [
            'name' => 'logo-carousel',
            'title' => 'Logo Carousel',
            'icon' => '',
            'child_name' => 'logo-carousel-child',
            'demo_link' => '',
        ],
        [
            'name' => 'twitter-feed-carousel',
            'title' => 'Twitter Feed Carousel',
            'icon' => '',
            'child_name' => '',
            'demo_link' => '',
        ],
    ];

    private $modules_pro = [
        [
            'name' => 'image-carousel',
            'title' => 'Image Carousel',
            'icon' => '',
            'child_name' => 'image-carousel-child',
            'demo_link' => '',
            'isPro' => true,
        ],
        [
            'name' => 'card-carousel',
            'title' => 'Card Carousel',
            'icon' => '',
            'child_name' => 'card-carousel-child',
            'demo_link' => '',
            'isPro' => true,
        ],
        [
            'name' => 'google-reviews',
            'title' => 'Google Reviews',
            'icon' => '',
            'child_name' => '',
            'demo_link' => '',
            'isPro' => true,
        ],
        [
            'name' => 'post-carousel',
            'title' => 'Post Carousel',
            'icon' => '',
            'child_name' => '',
            'demo_link' => '',
            'isPro' => true,
        ],
        [
            'name' => 'instagram-carousel',
            'title' => 'Instagram Carousel',
            'icon' => '',
            'child_name' => '',
            'demo_link' => '',
            'isPro' => true,
        ],
        [
            'name' => 'team-carousel',
            'title' => 'Team Carousel',
            'icon' => '',
            'child_name' => 'team-carousel-child',
            'demo_link' => '',
            'isPro' => true,
        ],
        [
            'name' => 'video-carousel',
            'title' => 'Video Carousel',
            'icon' => '',
            'child_name' => 'product-carousel-child',
            'demo_link' => '',
            'isPro' => true,
        ],
        [
            'name' => 'product-carousel',
            'title' => 'Product Carousel',
            'icon' => '',
            'child_name' => '',
            'demo_link' => '',
            'isPro' => true,
        ],
        [
            'name' => 'testimonial-carousel',
            'title' => 'Testimonial Carousel',
            'icon' => '',
            'child_name' => 'testimonial-child',
            'demo_link' => '',
            'isPro' => true,
        ],
        [
            'name' => 'logo-carousel',
            'title' => 'Logo Carousel',
            'icon' => '',
            'child_name' => 'logo-carousel-child',
            'demo_link' => '',
            'isPro' => true,
        ],
        [
            'name' => 'twitter-feed-carousel',
            'title' => 'Twitter Feed Carousel',
            'icon' => '',
            'child_name' => '',
            'demo_link' => '',
            'isPro' => true,
        ],
        [
            'name' => 'divi-library',
            'title' => 'Anything Carousel',
            'icon' => '',
            'child_name' => 'divi-library-child',
            'demo_link' => '',
            'isPro' => true,
        ],
    ];

    public static function get_instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct()
    {
        add_action('et_builder_ready', [$this, 'load_modules'], 9);
    }

    public static function get_all_pro_modules()
    {
        return self::get_instance()->modules_pro;
    }

    public static function get_all_modules()
    {
        return self::get_instance()->modules_lite;
    }

    public function load_modules()
    {
        if (!class_exists(\ET_Builder_Element::class)) {
            return;
        }

        $active_modules = $this->active_modules();

        foreach ($active_modules as $module_name => $module) {
            $module_path = sprintf('%1$s/divi4/modules/%2$s/%2$s.php', __DIR__, str_replace('-', '', ucwords($module_name, '-')));

            if (file_exists($module_path)) {
                require_once $module_path;
            }
        }
    }

    public function active_modules()
    {
        $all_modules = self::get_all_modules();
        $saved_modules = AdminHelper::get_modules();
        $active_modules = [];

        foreach ($all_modules as $module) {
            if (in_array($module['name'], $saved_modules)) {
                $active_modules[$module['name']] = $module;
                if (!empty($module['child_name'])) {
                    $active_modules[$module['child_name']] = $module;
                }
            }
        }

        return $active_modules;
    }
}
