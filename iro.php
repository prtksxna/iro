<?php
/*
Plugin Name:  Iro
Plugin URI:
Description:  Add color to posts, tags, and categories
Version:      1
Author:       prtksxna
Author URI:   http://prtksxna.com
Text Domain:  iro
Domain Path: /languages
License:      MIT
License URI:
*/

/*
Links to relevant documentation:
https://pippinsplugins.com/adding-custom-meta-fields-to-taxonomies/
https://make.wordpress.org/core/2015/09/04/taxonomy-term-metadata-proposal/
https://codex.wordpress.org/Version_4.4#For_Developers
https://www.sitepoint.com/wordpress-term-meta/
https://www.presstigers.com/how-to-add-wordpress-category-extra-fields/
https://www.ibenic.com/custom-fields-wordpress-taxonomies/
*/

// TODO Decide code style and indentations
// TODO Comment on ibenic.com
// TODO Set better defaults for the color

/* i18n */
function iro_load_plugin_textdomain() {
    load_plugin_textdomain( 'iro', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'iro_load_plugin_textdomain' );

/* Hooks */
add_action( 'category_add_form_fields', 'iro_add_colors' );
add_action( 'category_edit_form_fields', 'iro_edit_colors' );
add_action( 'create_category', 'iro_save_colors' );
add_action( 'edited_category', 'iro_save_colors' );

add_action( 'post_tag_add_form_fields', 'iro_add_colors' );
add_action( 'post_tag_edit_form_fields', 'iro_edit_colors' );
add_action( 'create_post_tag', 'iro_save_colors' );
add_action( 'edited_post_tag', 'iro_save_colors' );

/* Methods */
/**
 * Add fields for colors
 */
function iro_add_colors() {
	?>
  <div class="form-field">
    <label for="fg_color"><?php _e( 'Foreground color', 'iro' ); ?></label>
    <input type="color" name="fg_color" id="fg_color" value="">
    <p class="description"><?php _e( 'Foreground color that can be used by the theme','iro' ); ?></p>
  </div>
	<div class="form-field">
		<label for="bg_color"><?php _e( 'Background color', 'iro' ); ?></label>
		<input type="color" name="bg_color" id="bg_color" value="">
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
  if ( isset( $_POST[ 'fg_color' ] ) ) {
    $term_fg_color = $_POST[ 'fg_color' ];
    if ( $term_fg_color ) {
      update_term_meta( $term_id, 'fg_color', $term_fg_color) ;
    }
  }
  if ( isset( $_POST[ 'bg_color' ] ) ) {
    $term_bg_color = $_POST[ 'bg_color' ];
    if ( $term_bg_color ) {
      update_term_meta( $term_id, 'bg_color', $term_bg_color );
    }
  }
}
