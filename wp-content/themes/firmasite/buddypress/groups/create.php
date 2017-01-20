<?php

/**
 * Fires at the top of the groups creation template file.
 *
 * @since BuddyPress (1.7.0)
 */
do_action( 'bp_before_create_group_page' ); ?>

<?php global $firmasite_settings; ?>
<div id="buddypress">

	<?php

	/**
	 * Fires before the display of group creation content.
	 *
	 * @since BuddyPress (1.6.0)
	 */
	do_action( 'bp_before_create_group_content_template' ); ?>

	<form action="<?php bp_group_creation_form_action(); ?>" method="post" id="create-group-form" class="standard-form margin-top margin-bot" enctype="multipart/form-data">

		<?php

		/**
		 * Fires before the display of group creation.
		 *
		 * @since BuddyPress (1.2.0)
		 */
		do_action( 'bp_before_create_group' ); ?>

		<div class="item-list-tabs no-ajax margin-bot" id="group-create-tabs" role="navigation">
			<ul class="nav nav-pills">

				<?php bp_group_creation_tabs(); ?>

			</ul>
		</div>

		<?php

		/** This action is documented in bp-templates/bp-legacy/buddypress/activity/index.php */
		do_action( 'template_notices' ); ?>

		<div class="item-body" id="group-create-body">

			<?php /* Group creation step 1: Basic group details */ ?>
			<?php if ( bp_is_group_creation_step( 'group-details' ) ) : ?>

				<?php

				/**
				 * Fires before the display of the group details creation step.
				 *
				 * @since BuddyPress (1.1.0)
				 */
				do_action( 'bp_before_group_details_creation_step' ); ?>

				<div>
					<label for="group-name"><?php _e( 'Group Name (required)', 'firmasite' ); ?></label>
					<input type="text" name="group-name" id="group-name" aria-required="true" value="<?php bp_new_group_name(); ?>" />
				</div>

				<div>
					<label for="group-desc"><?php _e( 'Group Description (required)', 'firmasite' ); ?></label>
					<?php $content = bp_get_new_group_description(); 
                    echo firmasite_wp_editor($content , 'group-desc' ); 
                    /*
					<textarea name="group-desc" id="group-desc" aria-required="true"><?php bp_new_group_description(); ?></textarea>
                    */
                    ?>
				</div>

				<?php

				/**
				 * Fires after the display of the group details creation step.
				 *
				 * @since BuddyPress (1.1.0)
				 */
				do_action( 'bp_after_group_details_creation_step' );
				do_action( 'groups_custom_group_fields_editable' ); // @Deprecated

				wp_nonce_field( 'groups_create_save_group-details' ); ?>

			<?php endif; ?>

			<?php /* Group creation step 2: Group settings */ ?>
			<?php if ( bp_is_group_creation_step( 'group-settings' ) ) : ?>

				<?php

				/**
				 * Fires before the display of the group settings creation step.
				 *
				 * @since BuddyPress (1.1.0)
				 */
				do_action( 'bp_before_group_settings_creation_step' ); ?>

				<h4 class="page-header"><?php _e( 'Privacy Options', 'firmasite' ); ?></h4>

				<div class="radio">
					<label><input type="radio" name="group-status" value="public"<?php if ( 'public' == bp_get_new_group_status() || !bp_get_new_group_status() ) { ?> checked="checked"<?php } ?> /> <strong><?php _e( 'This is a public group', 'firmasite' ); ?></strong></label>
					<ul class="">
						<li><?php _e( 'Any site member can join this group.', 'firmasite' ); ?></li>
						<li><?php _e( 'This group will be listed in the groups directory and in search results.', 'firmasite' ); ?></li>
						<li><?php _e( 'Group content and activity will be visible to any site member.', 'firmasite' ); ?></li>
					</ul>


					<label>
						<input type="radio" name="group-status" value="private"<?php if ( 'private' == bp_get_new_group_status() ) { ?> checked="checked"<?php } ?> />
						<strong><?php _e( 'This is a private group', 'firmasite' ); ?></strong>
					</label>
					<ul class="">
						<li><?php _e( 'Only users who request membership and are accepted can join the group.', 'firmasite' ); ?></li>
						<li><?php _e( 'This group will be listed in the groups directory and in search results.', 'firmasite' ); ?></li>
						<li><?php _e( 'Group content and activity will only be visible to members of the group.', 'firmasite' ); ?></li>
					</ul>


					<label>
						<input type="radio" name="group-status" value="hidden"<?php if ( 'hidden' == bp_get_new_group_status() ) { ?> checked="checked"<?php } ?> />
						<strong><?php _e('This is a hidden group', 'firmasite' ); ?></strong>
					</label>
					<ul class="">
						<li><?php _e( 'Only users who are invited can join the group.', 'firmasite' ); ?></li>
						<li><?php _e( 'This group will not be listed in the groups directory or search results.', 'firmasite' ); ?></li>
						<li><?php _e( 'Group content and activity will only be visible to members of the group.', 'firmasite' ); ?></li>
					</ul>

				</div>

				<h4 class="page-header"><?php _e( 'Group Invitations', 'firmasite' ); ?></h4>

				<p><?php _e( 'Which members of this group are allowed to invite others?', 'firmasite' ); ?></p>

				<div class="radio">
					<label>
						<input type="radio" name="group-invite-status" value="members"<?php bp_group_show_invite_status_setting( 'members' ); ?> />
						<strong><?php _e( 'All group members', 'firmasite' ); ?></strong>
					</label>
                </div>
                <div class="radio">
					<label>
						<input type="radio" name="group-invite-status" value="mods"<?php bp_group_show_invite_status_setting( 'mods' ); ?> />
						<strong><?php _e( 'Group admins and mods only', 'firmasite' ); ?></strong>
					</label>
                </div>
                <div class="radio">
					<label>
						<input type="radio" name="group-invite-status" value="admins"<?php bp_group_show_invite_status_setting( 'admins' ); ?> />
						<strong><?php _e( 'Group admins only', 'firmasite' ); ?></strong>
					</label>
				</div>

				<?php if ( bp_is_active( 'forums' ) ) : ?>

					<h4 class="page-header"><?php _e( 'Group Forums', 'firmasite' ); ?></h4>

					<?php if ( bp_forums_is_installed_correctly() ) : ?>

						<p><?php _e( 'Should this group have a forum?', 'firmasite' ); ?></p>

						<div class="checkbox">
							<label><input type="checkbox" name="group-show-forum" id="group-show-forum" value="1"<?php checked( bp_get_new_group_enable_forum(), true, true ); ?> /> <?php _e( 'Enable discussion forum', 'firmasite' ); ?></label>
						</div>
					<?php elseif ( is_super_admin() ) : ?>

						<p><?php printf( __( '<strong>Attention Site Admin:</strong> Group forums require the <a href="%s">correct setup and configuration</a> of a bbPress installation.', 'firmasite' ), bp_core_do_network_admin() ? network_admin_url( 'settings.php?page=bb-forums-setup' ) :  admin_url( 'admin.php?page=bb-forums-setup' ) ); ?></p>

					<?php endif; ?>

				<?php endif; ?>

				<?php

				/**
				 * Fires after the display of the group settings creation step.
				 *
				 * @since BuddyPress (1.1.0)
				 */
				do_action( 'bp_after_group_settings_creation_step' ); ?>

				<?php wp_nonce_field( 'groups_create_save_group-settings' ); ?>

			<?php endif; ?>

			<?php /* Group creation step 3: Avatar Uploads */ ?>
			<?php if ( bp_is_group_creation_step( 'group-avatar' ) ) : ?>

				<?php

				/**
				 * Fires before the display of the group avatar creation step.
				 *
				 * @since BuddyPress (1.1.0)
				 */
				do_action( 'bp_before_group_avatar_creation_step' ); ?>

				<?php if ( 'upload-image' == bp_get_avatar_admin_step() ) : ?>
				<div class="row">
					<div class="left-menu col-xs-12 col-md-4">

						<?php bp_new_group_avatar(); ?>

					</div><!-- .left-menu -->

					<div class="main-column col-xs-12 col-md-8">
                    	<div class="well">
						<p><?php _e( "Upload an image to use as a profile photo for this group. The image will be shown on the main group page, and in search results.", 'firmasite' ); ?></p>

						<p>
							<input type="file" name="file" id="file" />
							<input type="submit" class="btn btn-primary" name="upload" id="upload" value="<?php esc_attr_e( 'Upload Image', 'firmasite' ); ?>" />
							<input type="hidden" name="action" id="action" value="bp_avatar_upload" />
						</p>

						<p><?php _e( 'To skip the group profile photo upload process, hit the "Next Step" button.', 'firmasite' ); ?></p>
						</div>
                    </div><!-- .main-column -->
				</div>
					<?php
					/**
					 * Load the Avatar UI templates
					 *
					 * @since  BuddyPress (2.3.0)
					 */
					bp_avatar_get_templates(); ?>

				<?php endif; ?>

				<?php if ( 'crop-image' == bp_get_avatar_admin_step() ) : ?>

					<h4 class="page-header"><?php _e( 'Crop Group Profile Photo', 'firmasite' ); ?></h4>

					<img src="<?php bp_avatar_to_crop(); ?>" id="avatar-to-crop" class="avatar thumbnail" alt="<?php esc_attr_e( 'Profile photo to crop', 'firmasite' ); ?>" />

					<div id="avatar-crop-pane">
						<img src="<?php bp_avatar_to_crop(); ?>" id="avatar-crop-preview" class="avatar thumbnail" alt="<?php esc_attr_e( 'Profile photo preview', 'firmasite' ); ?>" />
					</div>

					<input type="submit" class="btn btn-primary" name="avatar-crop-submit" id="avatar-crop-submit" value="<?php esc_attr_e( 'Crop Image', 'firmasite' ); ?>" />

					<input type="hidden" name="image_src" id="image_src" value="<?php bp_avatar_to_crop_src(); ?>" />
					<input type="hidden" name="upload" id="upload" />
					<input type="hidden" id="x" name="x" />
					<input type="hidden" id="y" name="y" />
					<input type="hidden" id="w" name="w" />
					<input type="hidden" id="h" name="h" />

				<?php endif; ?>

				<?php

				/**
				 * Fires after the display of the group avatar creation step.
				 *
				 * @since BuddyPress (1.1.0)
				 */
				do_action( 'bp_after_group_avatar_creation_step' ); ?>

				<?php wp_nonce_field( 'groups_create_save_group-avatar' ); ?>

			<?php endif; ?>

			<?php /* Group creation step 4: Invite friends to group */ ?>
			<?php if ( bp_is_group_creation_step( 'group-invites' ) ) : ?>

				<?php

				/**
				 * Fires before the display of the group invites creation step.
				 *
				 * @since BuddyPress (1.1.0)
				 */
				do_action( 'bp_before_group_invites_creation_step' ); ?>

				<?php if ( bp_is_active( 'friends' ) && bp_get_total_friend_count( bp_loggedin_user_id() ) ) : ?>
				<div class="row">
					<div class="left-menu col-xs-12 col-md-4">

						<div id="invite-list">
							<ul class="list-unstyled">
								<?php bp_new_group_invite_friend_list(); ?>
							</ul>

							<?php wp_nonce_field( 'groups_invite_uninvite_user', '_wpnonce_invite_uninvite_user' ); ?>
						</div>

					</div><!-- .left-menu -->

					<div class="main-column col-xs-12 col-md-8">

						<div class="clearfix"></div><div id="message" class="info alert alert-info">
							<p><?php _e('Select people to invite from your friends list.', 'firmasite' ); ?></p>
						</div>

						<?php /* The ID 'friend-list' is important for AJAX support. */ ?>
						<ul id="friend-list" class="item-list list-unstyled margin-top">

						<?php if ( bp_group_has_invites() ) : ?>

							<?php while ( bp_group_invites() ) : bp_group_the_invite(); ?>

								<li id="<?php bp_group_invite_item_id(); ?>" class="well well-sm clearfix">

									<?php bp_group_invite_user_avatar(); ?>

									<h4 class="page-header"><?php bp_group_invite_user_link(); ?></h4>
									<span class="label label-default activity"><?php bp_group_invite_user_last_active(); ?></span>

									<div class="action">
										<a class="remove" href="<?php bp_group_invite_user_remove_invite_url(); ?>" id="<?php bp_group_invite_item_id(); ?>"><?php _e( 'Remove Invite', 'firmasite' ); ?></a>
									</div>
								</li>

							<?php endwhile; ?>

							<?php wp_nonce_field( 'groups_send_invites', '_wpnonce_send_invites' ); ?>

						<?php endif; ?>

						</ul>

					</div><!-- .main-column -->
				</div>
				<?php else : ?>

					<div class="clearfix"></div><div id="message" class="info alert alert-info">
						<p><?php _e( 'Once you have built up friend connections you will be able to invite others to your group.', 'firmasite' ); ?></p>
					</div>

				<?php endif; ?>

				<?php wp_nonce_field( 'groups_create_save_group-invites' ); ?>

				<?php

				/**
				 * Fires after the display of the group invites creation step.
				 *
				 * @since BuddyPress (1.1.0)
				 */
				do_action( 'bp_after_group_invites_creation_step' ); ?>

			<?php endif; ?>

			<?php

			/**
			 * Fires inside the group admin template.
			 *
			 * Allows plugins to add custom group creation steps.
			 *
			 * @since BuddyPress (1.1.0)
			 */
			do_action( 'groups_custom_create_steps' ); ?>

			<?php

			/**
			 * Fires before the display of the group creation step buttons.
			 *
			 * @since BuddyPress (1.1.0)
			 */
			do_action( 'bp_before_group_creation_step_buttons' ); ?>

			<?php if ( 'crop-image' != bp_get_avatar_admin_step() ) : ?>

				<div class="submit" id="previous-next">

					<?php /* Previous Button */ ?>
					<?php if ( !bp_is_first_group_creation_step() ) : ?>

						<input type="button" value="<?php esc_attr_e( 'Back to Previous Step', 'firmasite' ); ?>" id="group-creation-previous" name="previous" onclick="location.href='<?php bp_group_creation_previous_link(); ?>'" />

					<?php endif; ?>

					<?php /* Next Button */ ?>
					<?php if ( !bp_is_last_group_creation_step() && !bp_is_first_group_creation_step() ) : ?>

						<input type="submit" class="btn btn-primary" value="<?php esc_attr_e( 'Next Step', 'firmasite' ); ?>" id="group-creation-next" name="save" />

					<?php endif;?>

					<?php /* Create Button */ ?>
					<?php if ( bp_is_first_group_creation_step() ) : ?>

						<input type="submit" class="btn btn-primary" value="<?php esc_attr_e( 'Create Group and Continue', 'firmasite' ); ?>" id="group-creation-create" name="save" />

					<?php endif; ?>

					<?php /* Finish Button */ ?>
					<?php if ( bp_is_last_group_creation_step() ) : ?>

						<input type="submit" class="btn btn-primary" value="<?php esc_attr_e( 'Finish', 'firmasite' ); ?>" id="group-creation-finish" name="save" />

					<?php endif; ?>
				</div>

			<?php endif;?>

			<?php

			/**
			 * Fires after the display of the group creation step buttons.
			 *
			 * @since BuddyPress (1.1.0)
			 */
			do_action( 'bp_after_group_creation_step_buttons' ); ?>

			<?php /* Don't leave out this hidden field */ ?>
			<input type="hidden" name="group_id" id="group_id" value="<?php bp_new_group_id(); ?>" />

			<?php

			/**
			 * Fires and displays the groups directory content.
			 *
			 * @since BuddyPress (1.1.0)
			 */
			do_action( 'bp_directory_groups_content' ); ?>

		</div><!-- .item-body -->

		<?php

		/**
		 * Fires after the display of group creation.
		 *
		 * @since BuddyPress (1.2.0)
		 */
		do_action( 'bp_after_create_group' ); ?>

	</form>

	<?php

	/**
	 * Fires after the display of group creation content.
	 *
	 * @since BuddyPress (1.6.0)
	 */
	do_action( 'bp_after_create_group_content_template' ); ?>

</div>

<?php

/**
 * Fires at the bottom of the groups creation template file.
 *
 * @since BuddyPress (1.7.0)
 */
do_action( 'bp_after_create_group_page' ); ?>
