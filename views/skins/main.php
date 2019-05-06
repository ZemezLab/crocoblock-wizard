<div>
	<p><?php
		_e( 'Each skin comes with custom demo content and predefined set of plugins. Depending upon the selected skin the wizard will install required plugins and some demo post and pages.', 'crocoblock-wizard' );
	?></p>
	<cx-vui-tabs
		:invert="true"
		:in-panel="true"
		:value="'upload-skin'"
	>
		<cx-vui-tabs-panel
			v-for="( typeLabel, typeSlug ) in allowedTypes"
			:name="typeSlug"
			:label="typeLabel"
			:key="typeSlug"
		>
			<div class="cbw-skins-list">
				<cbw-skin
					v-for="( skin, slug ) in skinsByTypes[ typeSlug ]"
					:skin="skin"
					:key="typeSlug + slug"
				></cbw-skin>
			</div>
		</cx-vui-tabs-panel>
		<cx-vui-tabs-panel
			:name="'upload-skin'"
			:label="'<?php _e( 'Upload Yours', 'crocoblock-wizard' ); ?>'"
		>
			<cbw-skin-uploader></cbw-skin-uploader>
		</cx-vui-tabs-panel>
	</cx-vui-tabs>
</div>