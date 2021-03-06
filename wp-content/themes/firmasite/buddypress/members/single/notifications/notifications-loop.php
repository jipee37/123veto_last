<form action="" method="post" id="notifications-bulk-management">
<div class="modal firmasite-modal-static margin-top"><div class="modal-dialog"><div class="modal-content"><div class="modal-body">
	<table class="table notifications">
		<thead>
			<tr>
				<th class="icon"></th>
				<th class="bulk-select-all"><label class="sr-only bp-screen-reader-text" for="select-all-notifications"><?php _e( 'Select all', 'firmasite' ); ?></label><input id="select-all-notifications" type="checkbox"></th>
				<th class="title"><?php _e( 'Notification', 'firmasite' ); ?></th>
				<th class="date"><?php _e( 'Date Received', 'firmasite' ); ?></th>
				<th class="actions"><?php _e( 'Actions',    'firmasite' ); ?></th>
			</tr>
		</thead>

		<tbody>

			<?php while ( bp_the_notifications() ) : bp_the_notification(); ?>

				<tr>
					<td></td>
					<td class="bulk-select-check"><input id="<?php bp_the_notification_id(); ?>" type="checkbox" name="notifications[]" value="<?php bp_the_notification_id(); ?>" class="notification-check"></td>
					<td class="notification-description"><?php bp_the_notification_description();  ?></td>
					<td class="notification-since"><?php bp_the_notification_time_since();   ?></td>
					<td class="notification-actions"><?php bp_the_notification_action_links(); ?></td>
				</tr>

			<?php endwhile; ?>

		</tbody>
	</table>

	<div class="notifications-options-nav form-inline">
		<?php bp_notifications_bulk_management_dropdown(); ?>
	</div><!-- .notifications-options-nav -->

	<?php wp_nonce_field( 'notifications_bulk_nonce', 'notifications_bulk_nonce' ); ?>
    
</div></div></div></div>
</form>
