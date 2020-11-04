<?php
/*
Plugin Name:  Iro
Plugin URI:   https://github.com/twozeroone/iro
Description:  Add color to posts, tags, and categories
Version:      1
Author:       prtksxna
Author URI:   http://prtksxna.com
Text Domain:  iro
Domain Path:  /languages
License:      MIT
License URI:  https://github.com/twozeroone/iro/blob/master/LICENSE.md
*/

/* i18n */
function iro_load_plugin_textdomain() {
    load_plugin_textdomain( 'iro', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'iro_load_plugin_textdomain' );

/* Hooks */
add_action('add_meta_boxes', 'iro_add_meta_box');
add_action('save_post', 'iro_save_metadata');

add_action( 'category_add_form_fields', 'iro_add_colors' );
add_action( 'category_edit_form_fields', 'iro_edit_colors' );
add_action( 'create_category', 'iro_save_colors' );
add_action( 'edited_category', 'iro_save_colors' );

add_action( 'post_tag_add_form_fields', 'iro_add_colors' );
add_action( 'post_tag_edit_form_fields', 'iro_edit_colors' );
add_action( 'create_post_tag', 'iro_save_colors' );
add_action( 'edited_post_tag', 'iro_save_colors' );

add_action('add_meta_boxes_jetpack-portfolio', 'iro_add_meta_box');
add_action('save_post_jetpack-portfolio', 'iro_save_metadata');

/* Methods */
/**
 * Add fields for colors
 */
function iro_add_colors() {
	?>
  <div class="form-field">
    <label for="fg_color"><?php _e( 'Foreground color', 'iro' ); ?></label>
    <input type="color" name="fg_color" id="fg_color" value="#000000">
    <p class="description"><?php _e( 'Foreground color that can be used by the theme','iro' ); ?></p>
  </div>
	<div class="form-field">
		<label for="bg_color"><?php _e( 'Background color', 'iro' ); ?></label>
		<input type="color" name="bg_color" id="bg_color" value="#ffffff">
		<p class="description"><?php _e( 'Background color that can be used by the theme','iro' ); ?></p>
	</div>
<?php
}

/**
 * Edit fields for colors
 */
function iro_edit_colors( $term ) {
	$t_id = $term->term_id;
	$term_fg_color = get_term_meta( $t_id, 'fg_color', true );
  $term_bg_color = get_term_meta( $t_id, 'bg_color', true );
	?>
	<tr class="form-field">
		<th><label for="fg_color"><?php _e( 'Foreground color', 'iro' ); ?></label></th>

		<td>
			<input type="color" name="fg_color" id="fg_color" value="<?php echo esc_attr( $term_fg_color ) ? esc_attr( $term_fg_color ) : ''; ?>">
		</td>
	</tr>
  <tr class="form-field">
		<th><label for="bg_color"><?php _e( 'Background color', 'iro' ); ?></label></th>

		<td>
			<input type="color" name="bg_color" id="bg_color" value="<?php echo esc_attr( $term_bg_color ) ? esc_attr( $term_bg_color ) : ''; ?>">
		</td>
	</tr>
<?php
}

/**
 * Save colors
 */
function iro_save_colors( $term_id ) {
  if ( iro_get_color( 'fg' ) ) {
    update_term_meta( $term_id, 'fg_color', iro_get_color( 'fg' ) ) ;
  }
  if ( iro_get_color( 'bg' ) ) {
    update_term_meta( $term_id, 'bg_color', iro_get_color( 'bg' ) ) ;
  }
}

/**
 * Add meta box to post
 */
function iro_add_meta_box() {
 add_meta_box(
   'iro_color',
   'Colors',
   'iro_meta_box_html',
   [ 'post', 'page', 'jetpack-portfolio' ],
   'side'
 );
}

/**
 * Meta box HTML callback
 */
function iro_meta_box_html( $post ) {
  $bg_color = get_post_meta($post->ID, 'bg_color', true);
  $fg_color = get_post_meta($post->ID, 'fg_color', true);

  // Set default colors if nothing is set
  if ( $bg_color === "" ) {
    $bg_color = "#ffffff";
  }
  if ( $fg_color === "" ) {
    $fg_color = "#000000";
  }
  ?>
  <label for="fg_color"><?php _e( 'Foreground color', 'iro' ); ?></label>
  <input type="color" name="fg_color" id="fg_color" value="<?php echo esc_attr( $fg_color ); ?>">
  <p class="description"><?php _e( 'Foreground color that can be used by the theme','iro' ); ?></p>

  <label for="bg_color"><?php _e( 'Background color', 'iro' ); ?></label>
  <input type="color" name="bg_color" id="bg_color" value="<?php echo esc_attr( $bg_color ); ?>">
  <p class="description"><?php _e( 'Background color that can be used by the theme','iro' ); ?></p>
  <?php
}

/**
 * Save post metadata
 */
function iro_save_metadata( $post_id ) {
  if ( iro_get_color( 'bg') ) {
    update_post_meta( $post_id, 'bg_color', iro_get_color( 'bg') );
  }
  if ( iro_get_color( 'fg' ) ) {
    update_post_meta( $post_id, 'fg_color', iro_get_color( 'fg' ) );
  }
}

/**
 * Helper function to check if the color is set in $_POST and if its clean.
 *
 * @param string $color The prefix to the color, either 'fg' or 'bg'.
 * @return string|bool Either a string to save or false if the color is absent
 *   or not clean.
 */
function iro_get_color( $color ) {
  $key = $color . '_color';
  if (
    isset( $_POST[ $key ] ) &&
    is_null( sanitize_hex_color( $_POST[ $key ] ) ) === false &&
    sanitize_hex_color( $_POST[ $key ] ) !== ''
  ) {
    return sanitize_hex_color( $_POST[ $key ] );
  } else {
    return false;
  }
}
