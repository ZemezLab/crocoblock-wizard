<div class="cbw-clear-content">
	<?php if ( ! current_user_can( 'delete_users' ) ) {
		_e(
			'You don`t have permissions to replace content, please re-enter with admiistrator account',
			'crocoblock-wizard'
		);
	} else { ?>
	<div class="cbw-clear-content__message">
		<?php
			_e( 'Please, enter your WordPress user password to confirm and start content replacing.', 'crocoblock-wizard' );
		?>
		<br><strong class="cbw-clear-content__note"><?php
			_e( 'NOTE: All your content will be replaced (posts, pages, comments, attachments and terms)', 'crocoblock-wizard' );
		?></strong>
	</div>
	<div class="cbw-clear-content__controls">
		<cx-vui-input
			type="password"
			placeholder="<?php esc_html_e( 'Please, enter your password', 'crocoblock-wizard' ); ?>"
			:prevent-wrap="true"
			v-model="password"
		></cx-vui-input>
		<cx-vui-button
			size="mini"
			button-style="accent"
			:disabled="!password || success"
			:loading="loading"
			@click="clearContent"
		>
			<span slot="label"><?php _e( 'Confirm', 'crocoblock-wizard' ); ?></span>
		</cx-vui-button>
	</div>
	<div
		:class="{
			'cbw-clear-content__message': true,
			'cbw-clear-content__message--error': error && ! sucess,
			'cbw-clear-content__message--success': success,
		}"
	>{{ message }}</div>
	<?php } ?>
</div>