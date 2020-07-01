<div>
	<div class="cbw-block" v-if="hasTemplateAccess">
		<div
			class="cx-vui-component cx-vui-component--vertical-layout no-indents"
			v-if="isActivated"
		>
			<div class="cx-vui-component__label">{{ pageTitle }}</div>
		</div>
		<cx-vui-input
			v-else
			:element-id="'license_key'"
			:label="pageTitle"
			:size="'fullwidth'"
			:wrapper-css="[ 'vertical-layout' ]"
			:error="error"
			:placeholder="'<?php _e( 'Enter your license key', 'croco-ik' ); ?>'"
			@on-focus="clearErrors"
			@on-keyup="maybeChangeBtnLabel"
			v-model="licenseKey"
		></cx-vui-input>
		<div class="cbw-block__error-message" v-if="errorMessage && error">{{ errorMessage }}</div>
		<div class="cbw-block__success-message" v-if="successMessage && success">{{ successMessage }}</div>
		<br>
		<cx-vui-button
			:disabled="startLocked"
			:button-style="'accent'"
			:loading="loading"
			@click="activateLicense"
		>
			<span slot="label">{{ buttonLabel }}</span>
		</cx-vui-button>
		<div
			v-if="isActivated"
			class="cbw-deactivate-license"
		>
			<a
				:href="deactivateLink"
			><?php
				_e( 'Deactivate current license', 'croco-ik' );
			?></a>
		</div>
		<cbw-video-popup
			:url="videoURL"
			:active="showVideo"
			@close="showVideo = false"
		></cbw-video-popup>
	</div>
	<div class="cbw-block" v-else>
		<div class="cbw-license-miss-message"><?php
			_e( 'Your license doesn’t include Interactive Popup Library', 'crocoblock-wizard' );
		?></div>
		<div class="cbw-license-miss-actions">
			<cx-vui-button
				button-style="default"
				tag-name="a"
				:url="deactivateLink"
			>
				<span slot="label"><?php
					_e( 'Enter another license key', 'crocoblock-wizard' );
				?></span>
			</cx-vui-button>
			<cx-vui-button
				button-style="accent"
				tag-name="a"
				target="blank"
				url="https://crocoblock.com/upgrade/"
			>
				<span slot="label"><?php
					_e( 'Upgrade to All-Inclusive', 'crocoblock-wizard' );
				?></span>
			</cx-vui-button>
		</div>
	</div>
</div>