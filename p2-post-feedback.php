<?php
/**
 * Plugin Name:	P2 Post Feedback
 * Description:	This plugin allowes registered users to vote for the postings in the p2 theme.
 * Version:		1.0.2
 * Author:      HerrLlama for wpcoding.de
 * Author URI:  http://wpcoding.de
 * Licence:     GPLv3
 */

// check wp
if ( ! function_exists( 'add_action' ) )
	return;

// kickoff
add_action( 'plugins_loaded', 'pppf_init' );
function pppf_init() {

	// Check if P2 is active, return if not
	$theme = wp_get_theme();
	if ( $theme->name != 'P2' ) {
		if ( current_user_can( 'activate_plugins' ) ) {
			add_action( 'admin_notices', function() {
				?><div class="error"><p><?php _e( 'Sorry, this plugin needs the P2 Theme to be used.', 'p2-post-feedback-td' ); ?></p></div><?php
			} );
		}
		return;
	}

	// helpers
	require_once dirname( __FILE__ ) . '/inc/localization.php';

	// scripts
	require_once dirname( __FILE__ ) . '/inc/scripts.php';
	add_action( 'wp_enqueue_scripts', 'pppf_wp_enqueue_scripts' );

	// styles
	require_once dirname( __FILE__ ) . '/inc/styles.php';
	add_action( 'wp_enqueue_scripts', 'sf_wp_enqueue_styles' );

	// Ajax callbacks
	require_once dirname( __FILE__ ) . '/inc/ajax-rate-topic.php';
	add_action( 'wp_ajax_rate_topic', 'pppf_rate_topic' );

	// Topic display
	require_once dirname( __FILE__ ) . '/inc/the-content.php';
	add_action( 'p2_action_links', 'pppf_p2_action_links' );
	add_action( 'the_content', 'pppf_the_content' );

	// everything below is just in the admin panel
	if ( ! is_admin() )
		return;

	// meta boxes
	require_once dirname( __FILE__ ) . '/inc/add-meta-box.php';
	require_once dirname( __FILE__ ) . '/inc/save-meta-data.php';
	add_action( 'save_post', 'pppf_save_meta_data' );
}