<div class="export-skin">
	<div class="cbw-body__title"><?php
		_e( 'The skin settings to export', 'crocoblock-wizard' );
	?></div>
	<cx-vui-collapse
		:collapsed="false"
	>
		<h3 class="cx-vui-subtitle" slot="title"><?php _e( 'General Settings', 'crocoblock-wizard' ); ?></h3>
		<div slot="content">
			<cx-vui-switcher
				v-model="exportSettings.only_xml"
				:wrapper-css="['equalwidth']"
				label="<?php _e( 'Export only content', 'crocoblock-wizard' ); ?>"
				description="<?php _e( 'Activate this option in case you want to export only the content from your website without plugins.', 'crocoblock-wizard' ); ?>"
			></cx-vui-switcher>
			<cx-vui-input
				:wrapper-css="['equalwidth']"
				placeholder="<?php _e( 'The name of your skin', 'crocoblock-wizard' ); ?>"
				size="fullwidth"
				v-model="exportSettings.skin_name"
				label="<?php _e( 'The name of the skin', 'crocoblock-wizard' ); ?>"
				description="<?php _e( 'Enter the name of the skin to export.', 'crocoblock-wizard' ); ?>"
			></cx-vui-input>
			<cx-vui-input
				:wrapper-css="['equalwidth']"
				placeholder="<?php _e( 'Your skin’s URL', 'crocoblock-wizard' ); ?>"
				size="fullwidth"
				v-if="!exportSettings.only_xml"
				v-model="exportSettings.demo_url"
				label="<?php _e( 'Demo URL', 'crocoblock-wizard' ); ?>"
				description="<?php _e( 'Enter the URL for the preview of the exported skin (optional).', 'crocoblock-wizard' ); ?>"
			></cx-vui-input>
			<cx-vui-input
				:wrapper-css="['equalwidth']"
				placeholder="<?php _e( 'Thumbnail URL', 'crocoblock-wizard' ); ?>"
				size="fullwidth"
				v-if="!exportSettings.only_xml"
				v-model="exportSettings.thumb_url"
				label="<?php _e( 'Thumbnail URL', 'crocoblock-wizard' ); ?>"
				description="<?php _e( 'Enter the URL of an image of your skin’s preview.', 'crocoblock-wizard' ); ?>"
			></cx-vui-input>
			<cx-vui-switcher
				v-model="exportSettings.export_users"
				:wrapper-css="['equalwidth']"
				label="<?php _e( 'Export users with usermeta', 'crocoblock-wizard' ); ?>"
				description="<?php _e( 'By default users are not exported. Check this if you need to export all users with appropriate meta data.', 'crocoblock-wizard' ); ?>"
			></cx-vui-switcher>
		</div>
	</cx-vui-collapse>
	<cx-vui-collapse
		:collapsed="true"
	>
		<h3 class="cx-vui-subtitle" slot="title"><?php _e( 'Export Content Settings', 'crocoblock-wizard' ); ?></h3>
		<div slot="content">
			<cx-vui-textarea
				:wrapper-css="['equalwidth']"
				placeholder="<?php _e( 'Separate the names of the options using commas', 'crocoblock-wizard' ); ?>"
				size="fullwidth"
				v-model="exportSettings.export_options"
				label="<?php _e( 'Options for export', 'crocoblock-wizard' ); ?>"
				description="<?php _e( 'Enter the titles of the options for export. You can export any options, that are stored in the ’wp_options’ table. The name of the option should match the value from the ’option_name’ column for this table.', 'crocoblock-wizard' ); ?>"
			></cx-vui-textarea>
			<cx-vui-textarea
				:wrapper-css="['equalwidth']"
				placeholder="<?php _e( 'Separate the names of the tables  using commas', 'crocoblock-wizard' ); ?>"
				size="fullwidth"
				v-model="exportSettings.export_tables"
				label="<?php _e( 'Custom tables for export', 'crocoblock-wizard' ); ?>"
				description="<?php _e( 'Enter the names of the custom tables from the database for export. The names of the tables should be entered without any prefix.', 'crocoblock-wizard' ); ?>"
			></cx-vui-textarea>
		</div>
	</cx-vui-collapse>
	<cx-vui-collapse
		:collapsed="true"
		v-if="!exportSettings.only_xml"
	>
		<h3 class="cx-vui-subtitle" slot="title"><?php _e( 'Include Plugins', 'crocoblock-wizard' ); ?></h3>
		<div slot="content">
			<cx-vui-component-wrapper
				v-for="plugin in plugins"
				:key="plugin.slug"
				:label="plugin.name"
				:description="plugin.description"
				:wrapper-css="['equalwidth']"
			>
				<cx-vui-switcher
					:prevent-wrap="true"
					v-model="exportPlugins[ plugin.slug ].include"
					:wrapper-css="['include-switcher']"
				></cx-vui-switcher>
				<cx-vui-select
					:prevent-wrap="true"
					placeholder="<?php _e( 'Plugin Source', 'crocoblock-wizard' ); ?>"
					size="fullwidth"
					:wrapper-css="['source-select']"
					:conditions="[
						{
							input: exportPlugins[ plugin.slug ].include,
							compare: 'equal',
							value: true,
						}
					]"
					:options-list="[
						{
							value: 'wordpress',
							label: '<?php _e( 'WordPress plugins repository', 'crocoblock-wizard' ); ?>',
						},
						{
							value: 'crocoblock',
							label: '<?php _e( 'Crocoblock', 'crocoblock-wizard' ); ?>',
						},
						{
							value: 'remote',
							label: '<?php _e( 'Remote URL', 'crocoblock-wizard' ); ?>',
						},
					]"
					v-model="exportPlugins[ plugin.slug ].source"
				></cx-vui-select>
				<cx-vui-input
					:prevent-wrap="true"
					:wrapper-css="['zip-url']"
					:conditions="[
						{
							input: exportPlugins[ plugin.slug ].include,
							compare: 'equal',
							value: true,
						},
						{
							input: exportPlugins[ plugin.slug ].source,
							compare: 'equal',
							value: 'remote',
						},
						{
							'operator': 'AND',
						}
					]"
					placeholder="<?php _e( 'Plugin ZIP URL', 'crocoblock-wizard' ); ?>"
					size="fullwidth"
					v-model="exportPlugins[ plugin.slug ].url"
				></cx-vui-input>
			</cx-vui-component-wrapper>
		</div>
	</cx-vui-collapse>
	<div class="export-skin-actions">
		<cx-vui-button
			:loading="loading"
			button-style="accent"
			@click="exportSkin"
		><span slot="label"><?php _e( 'Export Skin', 'crocoblock-wizard' ); ?></span></cx-vui-button>
		<div
			class="cbw-export-error"
			v-if="error"
			v-html="errorMessage"
		></div>
	</div>
</div>