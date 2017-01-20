<h4 class="page-header"><?php _e( 'Change Profile Photo', 'firmasite' ); ?></h4>

<?php

/**
 * Fires before the display of profile avatar upload content.
 *
 * @since BuddyPress (1.1.0)
 */
do_action( 'bp_before_profile_avatar_upload_content' ); ?>

<?php if ( !(int)bp_get_option( 'bp-disable-avatar-uploads' ) ) : ?>

	<p><?php _e( 'Your profile photo will be used on your profile and throughout the site. If there is a <a href="http://gravatar.com">Gravatar</a> associated with your account email we will use that, or you can upload an image from your computer.', 'firmasite' ); ?></p>

	<form action="" method="post" id="avatar-upload-form" class="standard-form" enctype="multipart/form-data">

		<?php if ( 'upload-image' == bp_get_avatar_admin_step() ) : ?>

			<?php wp_nonce_field( 'bp_avatar_upload' ); ?>
			<p><?php _e( 'Click below to select a JPG, GIF or PNG format photo from your computer and then click \'Upload Image\' to proceed.', 'firmasite' ); ?></p>

			<p id="avatar-upload">
				<input type="file" name="file" id="file" />
				<input type="submit" class="btn btn-primary" name="upload" id="upload" value="<?php esc_attr_e( 'Upload Image', 'firmasite' ); ?>" />
				<input type="hidden" name="action" id="action" value="bp_avatar_upload" />
			</p>

			<?php if ( bp_get_user_has_avatar() ) : ?>
				<p><?php _e( "If you'd like to delete your current profile photo but not upload a new one, please use the delete profile photo button.", 'firmasite' ); ?></p>
				<p><a class="btn btn-default btn-xs button edit" href="<?php bp_avatar_delete_link(); ?>" title="<?php esc_attr_e( 'Delete Profile Photo', 'firmasite' ); ?>"><?php _e( 'Delete My Profile Photo', 'firmasite' ); ?></a></p>
			<?php endif; ?>

		<?php endif; ?>

		<?php if ( 'crop-image' == bp_get_avatar_admin_step() ) : ?>

			<h5><?php _e( 'Crop Your New Profile Photo', 'firmasite' ); ?></h5>

			<img src="<?php bp_avatar_to_crop(); ?>" id="avatar-to-crop" class="avatar thumbnail" alt="<?php esc_attr_e( 'Profile Photo to crop', 'firmasite' ); ?>" />

			<div id="avatar-crop-pane">
				<img src="<?php bp_avatar_to_crop(); ?>" id="avatar-crop-preview" class="avatar thumbnail" alt="<?php esc_attr_e( 'Profile Photo preview', 'firmasite' ); ?>" />
			</div>

			<input type="submit" class="btn btn-primary" name="avatar-crop-submit" id="avatar-crop-submit" value="<?php esc_attr_e( 'Crop Image', 'firmasite' ); ?>" />

			<input type="hidden" name="image_src" id="image_src" value="<?php bp_avatar_to_crop_src(); ?>" />
			<input type="hidden" id="x" name="x" />
			<input type="hidden" id="y" name="y" />
			<input type="hidden" id="w" name="w" />
			<input type="hidden" id="h" name="h" />

			<?php wp_nonce_field( 'bp_avatar_cropstore' ); ?>

		<?php endif; ?>

	</form>

	<?php
	/**
	 * Load the Avatar UI templates
	 *
	 * @since  BuddyPress (2.3.0)
	 */
	bp_avatar_get_templates(); ?>

<?php else : ?>

	<p><?php _e( 'Your profile photo will be used on your profile and throughout the site. To change your profile photo, please create an account with <a href="http://gravatar.com">Gravatar</a> using the same email address as you used to register with this site.', 'firmasite' ); ?></p>

<?php endif; ?>

<?php

/**
 * Fires after the display of profile avatar upload content.
 *
 * @since BuddyPress (1.1.0)
 */
do_action( 'bp_after_profile_avatar_upload_content' ); ?>
