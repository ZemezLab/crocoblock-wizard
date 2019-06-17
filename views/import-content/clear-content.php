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
	</div>
	<div class="cbw-clear-content__controls">
		<cx-vui-input
			type="password"
			placeholder="<?php esc_html_e( 'Please, enter your password', 'crocoblock-wizard' ); ?>"
			:prevent-wrap="true"
			v-model="password"
		></cx-vui-input>
		<cx-vui-button
			button-style="accent"
			:disabled="!password || success"
			:loading="loading"
			@click="clearContent"
		>
			<span slot="label"><?php _e( 'Confirm', 'crocoblock-wizard' ); ?></span>
		</cx-vui-button>
	</div>
	<div class="cbw-clear-content__note" v-if="! message">
		<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.71489 10.1136V6.71605H7.28511V10.1136H8.71489ZM8.71489 13.4716V11.7728H7.28511V13.4716H8.71489ZM0 16L8 0L16 16H0Z" fill="#C92C2C"/></svg>
		<?php _e( 'All your content will be replaced (posts, pages, comments, attachments and terms)', 'crocoblock-wizard' ); ?>
	</div>
	<div
		:class="{
			'cbw-clear-content__result': true,
			'cbw-clear-content__result--error': error && ! sucess,
			'cbw-clear-content__result--success': success,
		}"
	>{{ message }}</div>
	<?php } ?>
</div>