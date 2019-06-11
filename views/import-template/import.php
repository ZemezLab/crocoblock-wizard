<div class="cbw-import-template">
	<div
		:class="[
			'cbw-import-template__msg',
			'cbw-import-template__msg--' + result.type
		]"
	>{{ result.message }}</div>
	<div class="cbw-import-template__name">{{ template.name }}</div>
	<div class="cbw-import-template__thumb">
		<img
			:src="template.thumb"
			:alt="template.name"
		>
	</div>
	<div class="cbw-body__subtitle"><?php
		_e( 'Import to the library', 'crocoblock-wizard' );
	?></div>
	<p><?php
		_e( 'After the import, the page’s template will become available in the default Elementor templates list.', 'crocoblock-wizard' );
	?></p>
	<cx-vui-button
		button-style="accent"
		:loading="loading.template"
		@click="importTemplate"
	>
		<span slot="label" v-html="buttons.template"></span>
	</cx-vui-button>
	<p class="cbw-indent-top"><?php
		_e( 'or', 'crocoblock-wizard' );
	?></p>
	<div class="cbw-body__subtitle"><?php
		_e( 'Import and use as a page', 'crocoblock-wizard' );
	?></div>
	<p><?php
		_e( 'Use this option to create a page based on the template chosen for the import.' );
	?></p>
	<div class="cbw-import-template__controls">
		<cx-vui-input
			type="text"
			placeholder="<?php esc_html_e( 'Specify the page’s title', 'crocoblock-wizard' ); ?>"
			:prevent-wrap="true"
			v-if="!imported.page"
			v-model="pageTitle"
		></cx-vui-input>
		<cx-vui-button
			button-style="accent"
			:loading="loading.page"
			@click="createPage"
		>
			<span slot="label" v-html="buttons.page"></span>
		</cx-vui-button>
	</div>
	<div class="cbw-import-template__footer">
		<cx-vui-button
			@click="goBack"
		>
			<svg slot="label" width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.67089 0L-4.76837e-07 6L5.67089 12L7 10.5938L2.65823 6L7 1.40625L5.67089 0Z" fill="#007CBA"/></svg>
			<span slot="label"><?php _e( 'Back', 'crocoblock-wizard' ); ?></span>
		</cx-vui-button>
	</div>
</div>