=== GutenFactory§ ===
Contributors: ideag, khromov
Donate link: http://arunas.co/#coffee
Tags: Gutenberg, block, blocks
Requires at least: 4.9.0
Tested up to: 4.9.0
Stable tag: 0.1.0
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create Gutenberg blocks via PHP only (and, maybe, some CSS).

== Description ==

Since Gutenberg itself is JavaScript/React based, you also need to know JavaScript/React to create custom blocks. Or, you needed to know. GutenFactory, as the name suggests, aims to bring techniques of mass production into the new world of Gutenberg,

Once this plugin is activated, all you need to do is to pass some configuration options via PHP and add a pinch of CSS to make things look nice. Like this:

```
add_filter( 'gutenfactory_blocks', 'my_awesome_block' );
function my_awesome_block( $blocks ) {
  $blocks['arunas/my_awesome_block'] = [
    'name'          => 'My Awesome Block',
    'category'      => 'common',
    'style'         => plugins_url( 'my-awesome-block.css', __FILE__ ),
    'editor_style'  => plugins_url( 'my-awesome-block-editor.css', __FILE__ ),
    'callback'      => 'my_awesome_block_render',
    'fields'        => [
     // define the fields here.
    ]
  ];
}
```

GutenFactory will take this information, some standard Gutenberg components like TextControl and RichText and some custom JS to build a custom Gutenberg block for you, automatically.

In the plugin directory, you can find another plugin, my-cool-plugin, which illustrates how to create a simple Hero Image block using GutenFactory.

GutenFactory has been started on the Contributors day of WordCamp Norrköping 2018 by Arūnas Liuiza and Stanislav Khromov.

Note: This is still a very early work in progress, a lot features are still not implemented or can change in development. Do not use it on any kind of production environment.

== Installation ==

1. Upload `gutenfactory` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Use the `gutenfactory_blocks` filter to declare new blocks in your plugins/themes

== Frequently Asked Questions ==


== Changelog ==

= 0.1 =
Initial release

== Upgrade Notice ==

No upgrade notices

== Screenshots ==

No screenshots yet
