<?php

/**
 * BuddyPress - Users Header
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

<?php

/**
 * Fires before the display of a member's header.
 *
 * @since BuddyPress (1.2.0)
 */
do_action( 'bp_before_member_header' ); ?>

<div id="item-header-avatar" class="media-left">
	<a href="<?php bp_displayed_user_link(); ?>">

		<?php bp_displayed_user_avatar( 'type=full' ); ?>

	</a>
</div><!-- #item-header-avatar -->

<div id="item-header-content" class="media-body">

	<h2>
		<a href="<?php bp_displayed_user_link(); ?>"><?php bp_displayed_user_fullname(); ?></a>
	</h2>
    
	<?php if ( bp_is_active( 'activity' ) && bp_activity_do_mentions() ) : ?>
		<span class="user-nicename label label-info">@<?php bp_displayed_user_mentionname(); ?></span>
	<?php endif; ?>

	<span class="label label-default activity"><?php bp_last_activity( bp_displayed_user_id() ); ?></span>

	<?php

	/**
	 * Fires before the display of the member's header meta.
	 *
	 * @since BuddyPress (1.2.0)
	 */
	do_action( 'bp_before_member_header_meta' ); ?>

	<div id="item-meta">

		<?php if ( bp_is_active( 'activity' ) ) : ?>

			<blockquote id="latest-update">

				<?php bp_activity_latest_update( bp_displayed_user_id() ); ?>

			</blockquote>

		<?php endif; ?>

		<div id="item-buttons" class="clearfix">

			<?php

			/**
			 * Fires in the member header actions section.
			 *
			 * @since BuddyPress (1.2.6)
			 */
			do_action( 'bp_member_header_actions' ); ?>

		</div><!-- #item-buttons -->

		<?php

		 /**
		  * Fires after the group header actions section.
		  *
		  * If you'd like to show specific profile fields here use:
		  * bp_member_profile_data( 'field=About Me' ); -- Pass the name of the field
		  *
		  * @since BuddyPress (1.2.0)
		  */
		 do_action( 'bp_profile_header_meta' );

		 ?>

	</div><!-- #item-meta -->

</div><!-- #item-header-content -->

<?php

/**
 * Fires after the display of a member's header.
 *
 * @since BuddyPress (1.2.0)
 */
do_action( 'bp_after_member_header' ); ?>