<div class="export-skin">
	<div class="cbw-body__title"><?php
		_e( 'Configure Skin For Export', 'crocoblock-wizard' );
	?></div>
	<cx-vui-collapse
		:collapsed="false"
	>
		<h3 class="cx-vui-subtitle" slot="title"><?php _e( 'General Settings', 'crocoblock-wizard' ); ?></h3>
		<div slot="content">
			<cx-vui-switcher
				v-model="exportSettings.only_xml"
				:wrapper-css="['equalwidth']"
				label="<?php _e( 'Export only sample content', 'crocoblock-wizard' ); ?>"
				description="<?php _e( 'Check this if you want only to export sample content without other settings', 'crocoblock-wizard' ); ?>"
			></cx-vui-switcher>
			<cx-vui-input
				:wrapper-css="['equalwidth']"
				placeholder="<?php _e( 'Set exported skin name', 'crocoblock-wizard' ); ?>"
				size="fullwidth"
				v-model="exportSettings.skin_name"
				label="<?php _e( 'Set exported skin name', 'crocoblock-wizard' ); ?>"
				description="<?php _e( 'Setup exported skin name', 'crocoblock-wizard' ); ?>"
			></cx-vui-input>
			<cx-vui-input
				:wrapper-css="['equalwidth']"
				placeholder="<?php _e( 'Skin Demo', 'crocoblock-wizard' ); ?>"
				size="fullwidth"
				v-if="!exportSettings.only_xml"
				v-model="exportSettings.demo_url"
				label="<?php _e( 'Demo URL', 'crocoblock-wizard' ); ?>"
				description="<?php _e( 'Set URL with skin preview demo', 'crocoblock-wizard' ); ?>"
			></cx-vui-input>
			<cx-vui-input
				:wrapper-css="['equalwidth']"
				placeholder="<?php _e( 'Skin Thumbnail', 'crocoblock-wizard' ); ?>"
				size="fullwidth"
				v-if="!exportSettings.only_xml"
				v-model="exportSettings.thumb_url"
				label="<?php _e( 'Thumbnail URL', 'crocoblock-wizard' ); ?>"
				description="<?php _e( 'Set URL of exported skin preview image', 'crocoblock-wizard' ); ?>"
			></cx-vui-input>
		</div>
	</cx-vui-collapse>
	<cx-vui-collapse
		:collapsed="true"
	>
		<h3 class="cx-vui-subtitle" slot="title"><?php _e( 'Export Content Settings', 'crocoblock-wizard' ); ?></h3>
		<div slot="content">
			<cx-vui-textarea
				:wrapper-css="['equalwidth']"
				placeholder="<?php _e( 'Separate option names with commas', 'crocoblock-wizard' ); ?>"
				size="fullwidth"
				v-model="exportSettings.export_options"
				label="<?php _e( 'Options to Export', 'crocoblock-wizard' ); ?>"
				description="<?php _e( 'Set option names to export them with sample content', 'crocoblock-wizard' ); ?>"
			></cx-vui-textarea>
			<cx-vui-textarea
				:wrapper-css="['equalwidth']"
				placeholder="<?php _e( 'Separate table names with commas', 'crocoblock-wizard' ); ?>"
				size="fullwidth"
				v-model="exportSettings.export_tables"
				label="<?php _e( 'Options to Export', 'crocoblock-wizard' ); ?>"
				description="<?php _e( 'Set custom DataBase tables names to export them with sample content', 'crocoblock-wizard' ); ?>"
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
							label: '<?php _e( 'Wordpress plugins repository', 'crocoblock-wizard' ); ?>',
						},
						{
							value: 'crocoblock',
							label: '<?php _e( 'CrocoBlock', 'crocoblock-wizard' ); ?>',
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