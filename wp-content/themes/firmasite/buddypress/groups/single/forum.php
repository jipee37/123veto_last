<?php

/**
 * Fires before the display of a group's forum content.
 *
 * @since BuddyPress (1.2.0)
 */
do_action( 'bp_before_group_forum_content' );

if ( bp_is_group_forum_topic_edit() ) :
	bp_get_template_part( 'groups/single/forum/edit' );

elseif ( bp_is_group_forum_topic() ) :
	bp_get_template_part( 'groups/single/forum/topic' );

else : ?>

	<div class="item-list-tabs no-ajax" id="subnav" role="navigation">
		<ul class="nav nav-pills">

			<?php if ( is_user_logged_in() ) : ?>

				<li>
					<a href="#post-new" class="show-hide-new"><?php _e( 'New Topic', 'firmasite' ); ?></a>
				</li>

			<?php endif; ?>

			<?php if ( bp_forums_has_directory() ) : ?>

				<li>
					<a href="<?php bp_forums_directory_permalink(); ?>"><?php _e( 'Forum Directory', 'firmasite' ); ?></a>
				</li>

			<?php endif; ?>

			<?php

			/** This filter is documented in bp-templates/bp-legacy/buddypress/forums/index.php. */
			do_action( 'bp_forums_directory_group_sub_types' ); ?>

			<li id="forums-order-select" class="pull-right form-inline last filter">

				<label for="forums-order-by"><?php _e( 'Order By:', 'firmasite' ); ?></label>
				<select class="form-control input-sm" id="forums-order-by">
					<option value="active"><?php _e( 'Last Active', 'firmasite' ); ?></option>
					<option value="popular"><?php _e( 'Most Posts', 'firmasite' ); ?></option>
					<option value="unreplied"><?php _e( 'Unreplied', 'firmasite' ); ?></option>

					<?php

					/** This filter is documented in bp-templates/bp-legacy/buddypress/forums/index.php. */
					do_action( 'bp_forums_directory_order_options' ); ?>

				</select>
			</li>
		</ul>
	</div>

	<div class="forums single-forum margin-top">

		<?php bp_get_template_part( 'forums/forums-loop' ) ?>

	</div><!-- .forums.single-forum -->

<?php endif; ?>

<?php

/**
 * Fires after the display of a group's forum content.
 *
 * @since BuddyPress (1.2.0)
 */
do_action( 'bp_after_group_forum_content' ); ?>

<?php if ( !bp_is_group_forum_topic_edit() && !bp_is_group_forum_topic() ) : ?>

	<?php if ( !bp_group_is_user_banned() && ( ( is_user_logged_in() && 'public' == bp_get_group_status() ) || bp_group_is_member() ) ) : ?>

		<form action="" method="post" id="forum-topic-form" class="standard-form">
			<div id="new-topic-post">

				<?php

				/**
				 * Fires before the display of a group forum new post form.
				 *
				 * @since BuddyPress (1.2.0)
				 */
				do_action( 'bp_before_group_forum_post_new' ); ?>

				<?php if ( bp_groups_auto_join() && !bp_group_is_member() ) : ?>
					<p><?php _e( 'You will auto join this group when you start a new topic.', 'firmasite' ); ?></p>
				<?php endif; ?>

				<p id="post-new"></p>
				<h4 class="page-header"><?php _e( 'Post a New Topic:', 'firmasite' ); ?></h4>

				<label><?php _e( 'Title:', 'firmasite' ); ?></label>
				<input type="text" name="topic_title" id="topic_title" value="" maxlength="100" />

				<label><?php _e( 'Content:', 'firmasite' ); ?></label>
				<textarea name="topic_text" id="topic_text"></textarea>

				<label><?php _e( 'Tags (comma separated):', 'firmasite' ); ?></label>
				<input type="text" name="topic_tags" id="topic_tags" value="" />

				<?php

				/**
				 * Fires after the display of a group forum new post form.
				 *
				 * @since BuddyPress (1.2.0)
				 */
				do_action( 'bp_after_group_forum_post_new' ); ?>

				<div class="submit">
					<input type="submit" class="btn btn-primary" name="submit_topic" id="submit" value="<?php esc_attr_e( 'Post Topic', 'firmasite' ); ?>" />
				</div>

				<?php wp_nonce_field( 'bp_forums_new_topic' ); ?>
			</div><!-- #new-topic-post -->
		</form><!-- #forum-topic-form -->

	<?php endif; ?>

<?php endif; ?>

