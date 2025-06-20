<?php
/*
* Plugin Name: Shortcodes for Divi
* Plugin URI:  https://wpzone.co/
* Description: Create Shortcodes from Divi Library Layouts
* Version:     1.2.2
* Author:      WP Zone
* Author URI:  https://wpzone.co/
* License:     GPLv3
* License URI: http://www.gnu.org/licenses/gpl.html
* Text Domain: shortcodes-for-divi
* Domain Path: /languages
* Update URI:  https://wordpress.org/plugins/shortcodes-for-divi/
*/

/*
Despite the following, this project is licensed exclusively
under GNU General Public License (GPL) version 3 (no future versions).
This statement modifies the following text.

Shortcodes for Divi plugin
Copyright (C) 2024  WP Zone

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.

========

Credits:

This plugin includes code based on and/or copied from parts of WordPress
by Automatic, released under the GNU General Public License (GPL) version 2 or later,
licensed under GPL version 3 or later (see ./license.txt file).

This plugin includes code based on and/or copied from parts of the Divi theme, copyright Elegant Themes,
released under the GNU General Public License (GPL) version 2, licensed under GPL
version 3 for this project by special permission (see ./license.txt file).


=======

Note:

Divi is a registered trademark of Elegant Themes, Inc. This product is
not affiliated with nor endorsed by Elegant Themes.

*/


// Create New Column in the Divi Library
add_filter( 'manage_et_pb_layout_posts_columns', 'ds_shortcode_create_shortcode_column', 5 );
add_action( 'manage_et_pb_layout_posts_custom_column', 'ds_shortcode_content', 5, 2 );
// Register New Divi Space Shortcode
if (! shortcode_exists('divi_library_layout')) {
	add_shortcode( 'divi_library_layout', 'ds_shortcode_callback' );
}

if (! shortcode_exists('ds_layout_sc')) {
	add_shortcode( 'ds_layout_sc', 'ds_shortcode_callback' );
}
// Load translations
add_action( 'plugins_loaded', function() {
    load_plugin_textdomain( 'shortcodes-for-divi', false, false );
});
// Link to Divi Library
add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ds_shortcode_action_links' );


// Create New Admin Column
function ds_shortcode_create_shortcode_column( $columns ) {
    $columns['ds_shortcode_id'] = __( 'Shortcode', 'shortcodes-for-divi');
    return $columns;
}

// Display Shortcode
function ds_shortcode_content ( $column, $id ) {
    if( 'ds_shortcode_id' == $column ) {
            ?>
            <p>[divi_library_layout id="<?php echo esc_attr( $id ) ?>"]</p>
            <?php
        }
}

// Create New Shortcode
function ds_shortcode_callback ( $ds_cb_id ) {
    $ds_cb_arg = shortcode_atts( array ('id' =>'*'), $ds_cb_id );
    $id = (int) $ds_cb_arg['id'];
	
	$isVbPreview = function_exists('is_et_pb_preview') && is_et_pb_preview();
	if ( $isVbPreview ) {
		add_filter('pre_do_shortcode_tag', 'ds_shortcode_set_ajax_module_index');
	}
    return do_shortcode( '[et_pb_section global_module="' . esc_attr( $id ) . ' "][/et_pb_section]' );
	
	if ( $isVbPreview ) {
		global $et_pb_predefined_module_index, $ds_pbe_module_index_before;
		if (isset($ds_pbe_module_index_before)) {
			$et_pb_predefined_module_index = $ds_pbe_module_index_before;
			unset($ds_pbe_module_index_before);
		} else {
			unset($et_pb_predefined_module_index);
		}
		remove_filter('pre_do_shortcode_tag', 'ds_shortcode_set_ajax_module_index');
	}
}

// Set a random high module index when rendering in the visual builder to avoid conflicts with other modules on the same page
function ds_shortcode_set_ajax_module_index($value) {
	global $et_pb_predefined_module_index, $ds_pbe_module_index, $ds_pbe_module_index_before;
	if (!isset($ds_pbe_module_index)) {
		$ds_pbe_module_index = wp_rand(999, 999999);
		if (isset($et_pb_predefined_module_index)) {
			$ds_pbe_module_index_before = $et_pb_predefined_module_index;
		}
	}
	$et_pb_predefined_module_index = ++$ds_pbe_module_index;
	
	return $value;
}

// Link to Divi library
function ds_shortcode_action_links( $links ) {

    $links = array_merge( array(
        '<a href="' . esc_url( admin_url( '/edit.php?post_type=et_pb_layout' ) ) . '">' . __( 'Divi Library', 'shortcodes-for-divi' ) . '</a>'
    ), $links );

    return $links;

}