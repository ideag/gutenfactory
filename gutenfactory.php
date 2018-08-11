<?php
/**
 * Plugin Name: GutenFactory
 * Version: 0.1.0
 */

$gutenfactory_blocks = [
  'namespace/name' => [
    'title' => __( 'My Block', 'gutenfactory' ),
    'category' => 'common',
    'fields'  => [
      'content' => [
        'control' => 'RichText',
        'display' => 'div',
      ],
      'background' => [
        'control' => 'MediaLibrary',
        'display' => 'css',
      ]
    ],
    'style' =>   plugins_url( 'my-block.css', __FILE__ ),
    'editor_style' =>   plugins_url( 'my-block-editor.css', __FILE__ ),
  ],
  'namespace/name2' => [
    'title' => __( 'My Block2', 'gutenfactory' ),
    'category' => 'common',
    'fields'  => [
      'content' => [
        'control' => 'RichText',
        'display' => 'div',
      ],
      // 'background' => [
      //   'control' => 'MediaLibrary',
      //   'display' => 'css',
      // ]
    ],
    'callback' => 'my_awesome_block',
    'style' =>   plugins_url( 'my-block.css', __FILE__ ),
    'editor_style' =>   plugins_url( 'my-block-editor.css', __FILE__ ),
  ],
];
function my_awesome_block( $fields ) {
  $output = '<div style="background-image:url('.$fields['background'].');">'.$fields['content'].'</div>';
  return $output;
}

function gutenfactory_init() {
  global $gutenfactory_blocks;
  $gutenfactory_blocks = apply_filters( 'gutenfactory_blocks', $gutenfactory_blocks );
}
add_action( 'plugins_loaded', 'gutenfactory_init', 100 );

function gutenfactory_run() {
  global $gutenfactory_blocks;
  $version = current_time( 'timestamp' );
  wp_register_script(
    'gutenfactory-editor',
    plugins_url( 'gutenfactory-editor.js', __FILE__ ),
    array(
      'wp-blocks',
      'wp-i18n',
      'wp-element',
    ),
    $version
  );
  foreach ( $gutenfactory_blocks as $name => $attributes ) {
    $slug = str_replace( '/', '-', $name );
    wp_register_style(
      $slug . '-editor',
      $attributes['editor_style'],
      array(
        'wp-blocks',
      ),
      $version
    );
    wp_register_style(
      $slug,
      $attributes['style'],
      array(
        'wp-blocks',
      ),
      $version
    );
    register_block_type( $name, array(
      'editor_script' => 'gutenfactory-editor',
      'editor_style'  => $slug . '-editor',
      'style'         => $slug,
    ) );
  }
  wp_localize_script( 'gutenfactory-editor', 'gutenfactory_blocks', $gutenfactory_blocks );
}
add_action( 'init', 'gutenfactory_run' );
