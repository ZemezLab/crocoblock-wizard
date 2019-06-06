<div class="cbw-select-plugins">
	<div class="cbw-block">
		<div class="cbw-body__title"><?php
			_e( 'Choose the theme to use with Crocoblock', 'crocoblock-wizard' );
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
					<div class="cbw-plugins-group__heading-title"><?php
						_e( 'Required plugins', 'crocoblock-wizard' );
					?></div>
					<div class="cbw-plugins-group__heading-desc" v-if="showRec"><?php
						_e( 'The recommended set of basic plugins to display the template’s pages. The best option for your site’s future configuration. If you will not install one or more plugins from this list, the specific sections of the template, for which these plugins are responsible, will not be displayed.', 'crocoblock-wizard' );
					?></div>
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
			<div class="cbw-plugins-group">
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
						_e( 'The full list of plugins available for a template installation is recommended if you want to get additional functionality to your theme.', 'crocoblock-wizard' );
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
		</cx-vui-button>&nbsp;
		<cx-vui-button
			:button-style="'accent'"
			@click="goToNextStep"
		>
			<span slot="label"><?php _e( 'Continue', 'crocoblock-wizard' ); ?></span>
			<svg slot="label" width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.32911 0L7 6L1.32911 12L0 10.5938L4.34177 6L0 1.40625L1.32911 0Z" fill="white"/></svg>
		</cx-vui-button>
		<cx-vui-button
			@click="skipPlugins"
		>
			<span slot="label"><?php _e( 'Skip', 'crocoblock-wizard' ); ?></span>
			<svg slot="label" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.32911 1L14 7L8.32911 13L7 11.5938L11.3418 7L7 2.40625L8.32911 1Z" fill="#007CBA"/><path d="M1.32911 1L7 7L1.32911 13L0 11.5938L4.34177 7L0 2.40625L1.32911 1Z" fill="#007CBA"/></svg>
		</cx-vui-button>
	</div>
</div>