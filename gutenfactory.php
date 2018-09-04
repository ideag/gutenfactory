<?php
/**
 * Plugin Name: GutenFactory
 * DescriptionL PHP based interface to generate Gutenberg blocks.
 * Version: 0.1.0
 * Author: Arūnas Liuiza & Stanislav Khromov
 * Author URI: https://arunas.co
 */

add_action( 'plugins_loaded', 'GutenFactory', 100 );

function GutenFactory() {
	if ( false === GutenFactoryClass::$instance ) {
		GutenFactoryClass::$instance = new GutenFactoryClass();
	}
	return GutenFactoryClass::$instance;
}

class GutenFactoryClass {
  static public $instance = false;
  public $blocks = [];
  public function __construct() {
    $this->blocks = apply_filters( 'gutenfactory_blocks', $this->blocks );
    add_action( 'init', array( $this, 'run' ) );
    add_filter( 'gutenfactory_fields', array( $this, 'prefill' ), 10, 2 );
  }
  public function run() {
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
    foreach ( $this->blocks as $name => $attributes ) {
      foreach ( $attributes['fields'] as $attr_name => $attr ) {
        $attr = apply_filters( 'gutenfactory_fields', $attr, $attr_name );
        $attributes['fields'][ $attr_name ] = $attr;
      }
      $slug = str_replace( '/', '-', $name );
      $attributes['slug'] = $slug;
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
        'editor_script'   => 'gutenfactory-editor',
        'editor_style'    => $slug . '-editor',
        'style'           => $slug,
        'attributes'      => $this->attributes( $attributes['fields'] ),
        'render_callback' => $attributes['callback'],
      ) );
      $gutenfactory_blocks[ $name ] = $attributes;
    }
    wp_localize_script( 'gutenfactory-editor', 'gutenfactory_blocks', $gutenfactory_blocks );
  }
  public function attributes( $attributes ) {
    $result = [];
    foreach ( $attributes as $key => $attribute ) {
      $result[ $key ] = [ 'type' => $attribute['data_type'] ];
    }
    return $result;
  }
  public function prefill( $attributes, $name ) {
    if ( ! isset( $attributes['data_type'] ) ) {
      $attributes['data_type'] = 'string';
    }
    if ( ! isset( $attributes['content'] ) ) {
      $attributes['content'] = '';
    }
    if ( ! isset( $attributes['label'] ) ) {
      $attributes['label'] = '';
    }
    if ( ! isset( $attributes['props'] ) ) {
      $attributes['props'] = [];
    }
    $attributes['props']['label'] = $attributes['label'];
    if ( isset( $attributes['help'] ) ) {
      $attributes['props']['help'] = $attributes['help'];
    }
    return $attributes;
  }
}

// $gutenfactory_blocks = [
  // 'namespace/name2' => [
  //   'title' => __( 'My Block2', 'gutenfactory' ),
  //   'category' => 'common',
  //   'fields'  => [
  //     'heading' => [
  //       'control' => 'RichText',
  //       'position'=> 'inline',
  //       'data_type' => 'string',
  //       'props' => [
  //         'format' => 'string',
  //         'tagName' => 'h2',
  //         'className' => 'heading',
  //         'placeholder' => 'coool heading',
  //       ],
  //     ],
  //     'content' => [
  //       'control' => 'RichText',
  //       'position'=> 'inline',
  //       'data_type' => 'string',
  //       'props' => [
  //         'format' => 'string',
  //         'tagName' => 'div',
  //         'className' => 'content',
  //         'placehoder' => 'cool text',
  //       ],
  //     ],
  //     'background' => [
  //       'control' => 'MediaUpload',
  //       'position'=> 'inspector',
  //       'label'  => __( 'Upload background image.', 'gutenfactory' ),
  //       'data_type'=>'integer',
  //     ],
  //     // 'description' => [
  //     //   'control'   => 'TextareaControl',
  //     //   'position'  => 'inspector',
  //     //   'label'     => __( 'My Block Description', 'gutenfactory' ),
  //     //   'help'      => __( 'Write something here, too.', 'gutenfactory' ),
  //     // ],
  //     // 'class' => [
  //     //   'control'   => 'TextControl',
  //     //   'position'  => 'inspector',
  //     //   'default'   => 'coool',
  //     //   'label'     => __( 'My Block Class', 'gutenfactory' ),
  //     //   'help'      => __( 'Write something here', 'gutenfactory' ),
  //     // ],
  //     // 'range' => [
  //     //   'control'   => 'RangeControl',
  //     //   'position'  => 'inspector',
  //     //   'label'     => __( 'My Range', 'gutenfactory' ),
  //     //   'default'   => 50,
  //     //   'props'     => [
  //     //     'min'   => 10,
  //     //     'max'   => 100,
  //     //     'step'  => 10,
  //     //   ],
  //     // ]
  //   ],
  //   'callback' => 'my_awesome_block',
  //   'style' =>   plugins_url( 'my-block.css', __FILE__ ),
  //   'editor_style' =>   plugins_url( 'my-block-editor.css', __FILE__ ),
  // ],
// ];
// function gutenfactory_prefill( $attributes, $name ) {
//   if ( ! isset( $attributes['data_type'] ) ) {
//     $attributes['data_type'] = 'string';
//   }
//   if ( ! isset( $attributes['content'] ) ) {
//     $attributes['content'] = '';
//   }
//   if ( ! isset( $attributes['label'] ) ) {
//     $attributes['label'] = '';
//   }
//   if ( ! isset( $attributes['props'] ) ) {
//     $attributes['props'] = [];
//   }
//   $attributes['props']['label'] = $attributes['label'];
//   if ( isset( $attributes['help'] ) ) {
//     $attributes['props']['help'] = $attributes['help'];
//   }
//   return $attributes;
// }

// function gutenfactory_init() {
//   global $gutenfactory_blocks;
//   $gutenfactory_blocks = apply_filters( 'gutenfactory_blocks', $gutenfactory_blocks );
// }
// add_action( 'plugins_loaded', 'gutenfactory_init', 100 );

// function gutenfactory_run() {
//   global $gutenfactory_blocks;
//   $version = current_time( 'timestamp' );
//   wp_register_script(
//     'gutenfactory-editor',
//     plugins_url( 'gutenfactory-editor.js', __FILE__ ),
//     array(
//       'wp-blocks',
//       'wp-i18n',
//       'wp-element',
//     ),
//     $version
//   );
//   foreach ( $gutenfactory_blocks as $name => $attributes ) {
//     foreach ( $attributes['fields'] as $attr_name => $attr ) {
//       $attributes['fields'][ $attr_name ] = gutenfactory_prefill( $attr, $attr_name );
//     }
//     $slug = str_replace( '/', '-', $name );
//     $attributes['slug'] = $slug;
//     wp_register_style(
//       $slug . '-editor',
//       $attributes['editor_style'],
//       array(
//         'wp-blocks',
//       ),
//       $version
//     );
//     wp_register_style(
//       $slug,
//       $attributes['style'],
//       array(
//         'wp-blocks',
//       ),
//       $version
//     );
//     register_block_type( $name, array(
//       'editor_script' => 'gutenfactory-editor',
//       'editor_style'  => $slug . '-editor',
//       'style'         => $slug,
//       'attributes'      => gutenfactory_attributes( $attributes['fields'] ),
//       'render_callback' => $attributes['callback'],
//     ) );
//     $gutenfactory_blocks[ $name ] = $attributes;
//   }
//   wp_localize_script( 'gutenfactory-editor', 'gutenfactory_blocks', $gutenfactory_blocks );
// }
// function gutenfactory_attributes( $attributes ) {
//   $result = [];
//   foreach ( $attributes as $key => $attribute ) {
//     $result[ $key ] = [ 'type' => $attribute['data_type'] ];
//   }
//   return $result;
// }
// add_action( 'init', 'gutenfactory_run' );
