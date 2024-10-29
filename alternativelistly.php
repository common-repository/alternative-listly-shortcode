<?php
/*
Plugin Name: Alternative List.ly shortcode plugin
Plugin URI: http://www.prometod.eu/en/better-faster-list-ly-wordpress-plugin/
Description: Embed faster lists from List.ly with this optimized shortcode plugin 
Version: 1.0.0
Author: Deyan Totev
Author URI: http://prometod.eu/en/
*/


/*  Copyright 2014  

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
//    begin of code for implementing text button within TinyMCE editor
//    Source:   https://www.gavick.com/blog/wordpress-tinymce-custom-buttons/#tc-section-1
add_action('admin_head', 'altlistly_add_my_tc_button');
function altlistly_add_my_tc_button() {
    global $typenow;
    // check user permissions
    if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
   	return;
    }
    // verify the post type
    if( ! in_array( $typenow, array( 'post', 'page' ) ) )
        return;
	// check if WYSIWYG is enabled
	if ( get_user_option('rich_editing') == 'true') {
		add_filter("mce_external_plugins", "altlistly_add_tinymce_plugin");
		add_filter('mce_buttons', 'altlistly_register_my_tc_button');
	}
}
function altlistly_add_tinymce_plugin($plugin_array) {
   	$plugin_array['altlistly_tc_button'] = plugins_url( '/shortcode.js', __FILE__ ); // CHANGE THE BUTTON SCRIPT HERE
        
   	return $plugin_array;
}
function altlistly_register_my_tc_button($buttons) {
   array_push($buttons, "altlistly_tc_button");
   return $buttons;
}
//    end of code for implementing text button
//-------------------------
//    begin of actual code creating the logic of the plugin
function listly_shortcode_function( $atts ) {
//    common code for taking the atributes of the shortcode
//    Note that the default value of 12 per page is set in the shortcode.js
            extract( shortcode_atts(
                            array(
                                    'url' => '',
                                    'number_per_page_listly' => '',
                            ), $atts )
                    );
//    Each user of List.ly is registered internally with three digit code(for example '6Ri')
//    As every URL of list starts in the same manner, it is pretty common how to get this code
        $listly_user_code = $url[20].$url[21].$url[22];
//    Setting the code that this shortcode will output
//    Variable $number_per_page_listly is used to give the user option to set number of items per page
//    The button in TinyMCE editor will output [listly_shortcode url="" number_per_page_listly="12"] which is set in shortcode.js
        $end_data = "<div style='text-align:left' id='ly_wrap_$listly_user_code'><script type='text/javascript' src='https://list.ly/plugin/show?list=$listly_user_code&layout=gallery&show_header=false&show_author=false&show_tools=true&show_sharing=true&per_page=$number_per_page_listly'></script></div>";
        return $end_data;
}
add_shortcode( 'listly_shortcode', 'listly_shortcode_function' );
