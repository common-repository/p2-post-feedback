<?php
/**
 * Feature Name: Scripts
 * Author:       HerrLlama for wpcoding.de
 * Author URI:   http://wpcoding.de
 * Licence:      GPLv3
 */

/**
 * Loads the scripts
 *
 * @wp-hook	wp_enqueue_scripts
 * @return	void
 */
function pppf_wp_enqueue_scripts() {

	$script_suffix = '.js';
	if ( defined( 'WP_DEBUG' ) )
		$script_suffix = '.dev.js';

	wp_register_script(
		'pppf-frontend-scripts',
		plugin_dir_url( __FILE__ ) . '../js/frontend' . $script_suffix,
		array( 'jquery' )
	);

	wp_enqueue_script( 'pppf-frontend-scripts' );

	wp_localize_script( 'pppf-frontend-scripts', 'pppf_vars', array(
		'ajaxurl' => get_admin_url( NULL, 'admin-ajax.php' )
	) );
};