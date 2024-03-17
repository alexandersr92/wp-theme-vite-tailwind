<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

// functions.php is empty so you can easily track what code is needed in order to Vite + Tailwind JIT run well


// Main switch to get frontend assets from a Vite dev server OR from production built folder
// it is recommended to move it into wp-config.php
define('IS_VITE_DEVELOPMENT', true);


include "inc/inc.vite.php";


register_nav_menus(
    array(
        'primary-menu' => __('Primary Menu'),
        'footer-menu' => __('Footer Menu'),
    )
);

include_once('inc/acf_blocks.php');
include_once('inc/cpt.php');

function add_additional_class_on_li($classes, $item, $args)
{
    if (isset($args->add_li_class)) {
        $classes[] = $args->add_li_class;
    }
    return $classes;
}
add_filter('nav_menu_css_class', 'add_additional_class_on_li', 1, 3);

function add_file_types_to_uploads($file_types)
{
    $new_filetypes = array();
    $new_filetypes['svg'] = 'image/svg+xml';
    $file_types = array_merge($file_types, $new_filetypes);
    return $file_types;
}
add_filter('upload_mimes', 'add_file_types_to_uploads');
add_theme_support('post-thumbnails');

add_action('rest_api_init', 'register_rest_images');
function register_rest_images()
{
    register_rest_field(
        array('post'),
        'fimg_url',
        array(
            'get_callback'    => 'get_rest_featured_image',
            'update_callback' => null,
            'schema'          => null,
        )
    );
}
function get_rest_featured_image($object, $field_name, $request)
{
    if ($object['featured_media']) {
        $img = wp_get_attachment_image_src($object['featured_media'], 'app-thumb');
        if ($img) {
            return $img[0];
        } else {
        }
        return $img[0];
    }
    return get_template_directory_uri() . '/assets/img/dummy.webp';;
}
