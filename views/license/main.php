<div class="cbw-block">
	<div class="cbw-block__top">
		<cx-vui-input
			:element-id="'license_key'"
			:label="'<?php _e( 'License key:', 'crocoblock-wizard' ); ?>'"
			:size="'fullwidth'"
			:wrapper-css="[ 'vertical-layout' ]"
			:error="error"
			:placeholder="'<?php _e( 'Enter your license key', 'crocoblock-wizard' ); ?>'"
			@on-focus="clearErrors"
			v-model="licenseKey"
		></cx-vui-input>
		<div class="cbw-block__error-message" v-if="errorMessage && error">{{ errorMessage }}</div>
		<cbw-logger :log="log"></cbw-logger>
	</div>
	<div class="cbw-block__bottom">
		<cx-vui-button
			:disabled="!licenseKey"
			:button-style="'accent'"
			:loading="loading"
			@click="activateLicense"
		>
			<span slot="label"><?php _e( 'Start Install', 'crocoblock-wizard' ); ?></span>
		</cx-vui-button>
	</div>
</div>