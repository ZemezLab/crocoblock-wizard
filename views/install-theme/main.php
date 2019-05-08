<div class="cbw-block">
	<div class="cbw-block__top">
		<p><?php
			_e( 'We recommend yu to use our child themes generator to get child theme Kava', 'crocoblock-wizard' );
		?></p>
		<cbw-choices
			:choices="choices"
			v-model="nextStep"
		></cbw-choices>
		<cbw-logger :log="log"></cbw-logger>
	</div>
	<div class="cbw-block__bottom">
		<cx-vui-button
			@click="goToPrevStep"
		>
			<svg slot="label" width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.67089 0L-4.76837e-07 6L5.67089 12L7 10.5938L2.65823 6L7 1.40625L5.67089 0Z" fill="#007CBA"/></svg>
			<span slot="label"><?php _e( 'Back', 'crocoblock-wizard' ); ?></span>
		</cx-vui-button>&nbsp;
		<cx-vui-button
			:disabled="!nextStep"
			:button-style="'accent'"
			:loading="loading"
			@click="goToNextStep"
		>
			<span slot="label"><?php _e( 'Continue', 'crocoblock-wizard' ); ?></span>
			<svg slot="label" width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.32911 0L7 6L1.32911 12L0 10.5938L4.34177 6L0 1.40625L1.32911 0Z" fill="white"/></svg>
		</cx-vui-button>
	</div>
</div>