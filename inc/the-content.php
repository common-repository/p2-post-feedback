<?php
/**
 * Feature Name: Display the rating at the content
 * Author:       HerrLlama for wpcoding.de
 * Author URI:   http://wpcoding.de
 * Licence:      GPLv3
 */

/**
 * Adds the action link for the voting to
 * the actions of P2
 *
 * @wp-hook	p2_action_links
 * @return	void
 */
function pppf_p2_action_links() {
	global $post;
	echo ' | <a href="#" class="toggle-voting" topicid="' . $post->ID . '">' . __( 'Vating', 'p2-post-feedback-td' ) . '</a>';
}

/**
 * Displays the feedback boxes at the content
 *
 * @wp-hook	the_content
 * @param	string $content the content of the post
 * @return	string the content with the feedback boxes
 */
function pppf_the_content( $content ) {

	// Return if post type is topic
	if ( get_post_type() != 'post' )
		return $content;

	// Content at first
	$rtn = $content;

	// Check status
	$status = get_post_meta( get_the_ID(), 'topic-status', TRUE );
	if ( $status == 'open' )
		$class = '';
	else
		$class = 'inactive';

	// Check if user already rated this topic
	// Get list of voted users
	$voted_users = get_post_meta( get_the_ID(), 'topic-voted-users', TRUE );
	if ( ! is_array( $voted_users ) )
		$voted_users = array();

	if ( array_key_exists( get_current_user_id(), $voted_users ) )
		$class = 'inactive';
	else
		$class = '';

	if ( ! is_user_logged_in() )
		$class = 'inactive';

	// Build list of users
	$users_for_it = array();
	$users_against = array();
	$users_undecided = array();

	foreach ( $voted_users as $voted_user => $vote ) {

		if ( $vote == 'for-it' )
			$users_for_it[] = $voted_user;
		if ( $vote == 'against' )
			$users_against[] = $voted_user;
		if ( $vote == 'undecided' )
			$users_undecided[] = $voted_user;
	}

	// Lists
	ob_start();
	?>
	<div class="vote <?php echo $class; ?> vote-<?php the_ID(); ?>" topicid="<?php the_ID(); ?>">
		<ul class="for-it">
			<li class="btn">
				<?php _e( 'For it', 'p2-post-feedback-td' ); ?>
				(<span class="cnt"><?php echo count( $users_for_it ); ?></span>)
			</li>
			<?php
			foreach ( $users_for_it as $user_for_it ) {
				$user_data = get_userdata( $user_for_it );
				?>
				<li class="usr">
					<div class="avatar"><?php
						if ( is_user_logged_in() )
							echo get_avatar( $user_for_it, 40 );
					?></div>
					<div class="name">
						<?php
							if ( is_user_logged_in() )
								echo $user_data->display_name;
							else
								_e( 'Anonym', 'p2-post-feedback-td' );
						?>
					</div>
					<br class="clear">
				</li>
				<?php
			}
			?>
		</ul>
		<ul class="undecided">
			<li class="btn">
				<?php _e( 'Undecided', 'p2-post-feedback-td' ); ?>
				(<span class="cnt"><?php echo count( $users_undecided ); ?></span>)
			</li>
			<?php
			foreach ( $users_undecided as $user_undecided ) {
				$user_data = get_userdata( $user_undecided );
				?>
				<li class="usr">
					<div class="avatar"><?php
						if ( is_user_logged_in() )
							echo get_avatar( $user_undecided, 40 );
					?></div>
					<div class="name">
						<?php
							if ( is_user_logged_in() )
								echo $user_data->display_name;
							else
								_e( 'Anonym', 'p2-post-feedback-td' );
						?>
					</div>
					<br class="clear">
				</li>
				<?php
			}
			?>
		</ul>
		<ul class="against">
			<li class="btn">
				<?php _e( 'Against', 'p2-post-feedback-td' ); ?>
				(<span class="cnt"><?php echo count( $users_against ); ?></span>)
			</li>
			<?php
			foreach ( $users_against as $user_against ) {
				$user_data = get_userdata( $user_against );
				?>
				<li class="usr">
					<div class="avatar"><?php
						if ( is_user_logged_in() )
							echo get_avatar( $user_against, 40 );
					?></div>
					<div class="name">
						<?php
							if ( is_user_logged_in() )
								echo $user_data->display_name;
							else
								_e( 'Anonym', 'p2-post-feedback-td' );
						?>
					</div>
					<br class="clear">
				</li>
				<?php
			}
			?>
		</ul>
		<br class="clear">
	</div>
	<?php
	$list = ob_get_contents();
	ob_end_clean();

	return $rtn . $list;
}