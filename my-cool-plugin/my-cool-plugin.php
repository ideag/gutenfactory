<?php
/**
 * Plugin Name: GutenFactory - My cool plugin
 * Version: 0.1.0
 */

add_action( 'plugins_loaded', 'my_cool_plugin' );
function my_cool_plugin() {
  add_filter( 'gutenfactory_blocks', 'my_cool_plugin_block' );
}
function my_cool_plugin_block( $blocks ) {
  $blocks['mycoolplugin/block'] = [
    'title'     => __( 'Cool Block', 'my-cool-plugin' ),
    'category'  => 'common',
    'fields'    => [
      'heading'   => [
        'control'   => 'RichText',
        'position'  => 'inline',
        'data_type' => 'string',
        'props'     => [
          'format'      => 'string',
          'tagName'     => 'h2',
          'className'   => 'heading',
          'placeholder' => 'coool heading',
        ],
      ],
      'content'   => [
        'control'   => 'RichText',
        'position'  => 'inline',
        'data_type' => 'string',
        'props' => [
          'format'      => 'string',
          'tagName'     => 'div',
          'className'   => 'content',
          'placehoder'  => 'cool text',
        ],
      ],
      'background' => [
        'control'   => 'MediaUpload',
        'position'  => 'inspector',
        'label'     => __( 'Upload background image.', 'my-cool-plugin' ),
        'data_type' => 'integer',
      ],
    ],
    'callback'      => 'my_cool_plugin_callback',
    'style'         => plugins_url( 'my-cool-plugin.css', __FILE__ ),
    'editor_style'  => plugins_url( 'my-cool-plugin-editor.css', __FILE__ ),
  ];
  return $blocks;
}

function my_cool_plugin_callback( $fields, $content ) {
  $image = wp_get_attachment_image_src( $fields['background'], 'full' );
  $image = $image[0];
  $output  = '';
  $output .= '<div class="my-block bg" style="background-image:url(\'' . $image . '\');">';
  $output .= '<h2 class="heading">' . $fields['heading'] . '</h2>';
  $output .= '<div class="content">' . $fields['content'] . '</div>';
  $output .= '</div>';
  return $output;
}
