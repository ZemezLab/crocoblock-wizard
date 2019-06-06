<div class="cbw-select-theme-wrap">
	<div class="cbw-body__title"><?php _e( 'Choose the theme to use with Crocoblock', 'crocoblock-wizard' ); ?></div>
	<p><?php
		_e( 'Specify the theme you’d like to use with Crocoblock products.', 'crocoblock-wizard' );
	?></p>
	<div class="cbw-select-theme">
		<div class="cbw-select-theme__themes">
			<div
				v-for="( theme, slug ) in themes"
				class="cbw-select-theme__item"
				@click="startInstall( slug )"
			>
				<div class="cbw-select-theme__item-thumb">
					<img
						:src="theme.logo"
						alt=""
					>
				</div>
				<div class="cbw-select-theme__item-label"><?php
					_e( 'Start Install', 'crocoblock-wizard' );
				?></div>
			</div>
		</div>
		<div class="cbw-select-theme__action">
			<div class="cbw-select-theme__action-label"><?php
				_e( 'or', 'crocoblock-wizard' );
			?></div>
			<cx-vui-button
				:button-style="'accent'"
				tag-name="a"
				:url="nextCurrent"
			>
				<span slot="label">Continue with your Current Theme</span>
			</cx-vui-button>
		</div>
	</div>
	<div class="cbw-footer">
		<cx-vui-button
			@click="goToPrev"
		>
			<svg slot="label" width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.67089 0L-4.76837e-07 6L5.67089 12L7 10.5938L2.65823 6L7 1.40625L5.67089 0Z" fill="#007CBA"/></svg>
			<span slot="label"><?php _e( 'Back', 'crocoblock-wizard' ); ?></span>
		</cx-vui-button>
	</div>
</div>