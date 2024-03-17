<?php

function wp_block_setup()
{

  add_theme_support('editor-styles');
}

add_action('after_setup_theme', 'wp_block_setup');


function custom_block_category($categories, $post)
{
  return array_merge(
    $categories,
    array(
      array(
        'slug' => 'custom-blocks',
        'title' => __('Custom', 'custom'),
        'icon'  => 'wordpress',
      ),
    )
  );
}

add_filter('block_categories', 'custom_block_category', 10, 2);


function my_acf_block_render_callback($block)
{

  $slug = str_replace('acf/', '', $block['name']);

  if (file_exists(get_theme_file_path("/blocks/{$slug}.php"))) {
    include(get_theme_file_path("/blocks/{$slug}.php"));
  }
}
add_action('acf/init', 'my_acf_init');

function my_acf_init()
{
  //get all files in the blocks folder
  $blocks = glob(get_template_directory() . '/blocks/*.php');
  //

  //get just name of the file wothout the extension
  $blocks = array_map(function ($block) {
    return pathinfo($block)['filename'];
  }, $blocks);

  //register all blocks
  foreach ($blocks as $block) {
    if (function_exists('acf_register_block')) {
      $name = str_replace('-', ' ', $block);
      $name = ucwords($name);

      acf_register_block(array(
        'name'        => $block,
        'title'        => __($name),
        'description'    => __('A custom block.'),
        'render_callback'  => 'my_acf_block_render_callback',
        'category'      => 'custom-blocks',
        'icon'        => 'admin-comments',
        'keywords'      => array($block,),
      ));
    }
  }
}
