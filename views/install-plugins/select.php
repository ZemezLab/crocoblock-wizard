<div class="cbw-select-plugins">
	<div class="cbw-block">
		<div class="cbw-body__title"><?php
			_e( 'Configure plugins', 'crocoblock-wizard' );
		?></div>
		<div class="cbw-block__top">
			<div class="cbw-plugins-group">
				<div
					:class="{
						'cbw-plugins-group__heading': true,
						'cbw-plugins-group__heading--active': showRec,
					}"
					@click="showRec = !showRec"
				>
					<div
						class="cbw-plugins-group__heading-title"
						v-if="'full' === action"
					><?php
						_e( 'Required plugins', 'crocoblock-wizard' );
					?></div>
					<div v-else class="cbw-plugins-group__heading-title"><?php
						_e( 'Choose the plugins to install', 'crocoblock-wizard' );
					?></div>
					<div class="cbw-plugins-group__heading-desc" v-if="showRec">
						<div v-if="'full' === action">
							<div><?php
								_e( 'A basic set of required plugins. They are indispensable for the skin pages to work smoothly.', 'crocoblock-wizard' );
							?></div>
							<div class="cbw-warning">
								<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.71489 10.1136V6.71605H7.28511V10.1136H8.71489ZM8.71489 13.4716V11.7728H7.28511V13.4716H8.71489ZM0 16L8 0L16 16H0Z" fill="#C92C2C"/></svg>
								<?php _e( 'If you don\'t install one or more plugins from this list, the specific sections of the skin displayed via this plugin will be missing. ', 'crocoblock-wizard' ); ?>
							</div>
						</div>
						<span v-else><?php
							_e( 'You can find the full list of the Crocoblock plugins available for your license key below. Choose the ones you want to install and clock “Continue”', 'crocoblock-wizard' );
						?></span>
					</div>
				</div>
				<div
					class="cbw-plugins-group__body"
					v-if="showRec"
				>
					<cx-vui-checkbox
						name="recommended"
						return-type="array"
						:wrapper-css="[ 'check-group' ]"
						:options-list="skinPlugins"
						v-model="selectedSkinPlugins"
						@on-change="emitPluginsToInstall"
					></cx-vui-checkbox>
				</div>
			</div>
			<div
				class="cbw-plugins-group"
				v-if="'full' === action"
			>
				<div
					:class="{
						'cbw-plugins-group__heading': true,
						'cbw-plugins-group__heading--active': showExtra,
					}"
					@click="showExtra = !showExtra"
				>
					<div class="cbw-plugins-group__heading-title"><?php
						_e( 'Extra plugins', 'crocoblock-wizard' );
					?></div>
					<div class="cbw-plugins-group__heading-desc" v-if="showExtra"><?php
						_e( 'The full list of plugins available for installation. It is recommended if you want to add additional functionality to your website.', 'crocoblock-wizard' );
					?></div>
				</div>
				<div
					class="cbw-plugins-group__body"
					v-if="showExtra"
				>
					<cx-vui-checkbox
						name="recommended"
						return-type="array"
						:wrapper-css="[ 'check-group' ]"
						:options-list="exraPlugins"
						v-model="selectedExtraPlugins"
						@on-change="emitPluginsToInstall"
					></cx-vui-checkbox>
				</div>
			</div>
		</div>
	</div>
	<div class="cbw-footer">
		<cx-vui-button
			@click="goToPrevStep"
		>
			<svg slot="label" width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.67089 0L-4.76837e-07 6L5.67089 12L7 10.5938L2.65823 6L7 1.40625L5.67089 0Z" fill="#007CBA"/></svg>
			<span slot="label"><?php _e( 'Back', 'crocoblock-wizard' ); ?></span>
		</cx-vui-button>
		<cx-vui-button
			:button-style="'accent'"
			@click="goToNextStep"
		>
			<span slot="label"><?php _e( 'Continue', 'crocoblock-wizard' ); ?></span>
			<svg slot="label" width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.32911 0L7 6L1.32911 12L0 10.5938L4.34177 6L0 1.40625L1.32911 0Z" fill="white"/></svg>
		</cx-vui-button>
		<cx-vui-button
			@click="skipPlugins"
			v-if="'full' === action"
		>
			<span slot="label"><?php _e( 'Skip', 'crocoblock-wizard' ); ?></span>
			<svg slot="label" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.32911 1L14 7L8.32911 13L7 11.5938L11.3418 7L7 2.40625L8.32911 1Z" fill="#007CBA"/><path d="M1.32911 1L7 7L1.32911 13L0 11.5938L4.34177 7L0 2.40625L1.32911 1Z" fill="#007CBA"/></svg>
		</cx-vui-button>
	</div>
</div>