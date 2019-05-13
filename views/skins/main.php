<div>
	<p><?php
		_e( 'Each skin comes with custom demo content and predefined set of plugins. Depending upon the selected skin the wizard will install required plugins and some demo post and pages.', 'crocoblock-wizard' );
	?></p>
	<cx-vui-tabs
		:invert="true"
		:in-panel="true"
		value="skin"
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
					:slug="slug"
					:key="typeSlug + slug"
				></cbw-skin>
			</div>
		</cx-vui-tabs-panel>
		<cx-vui-tabs-panel
			name="upload-skin"
			label="<?php _e( 'Upload Yours', 'crocoblock-wizard' ); ?>"
		>
			<div
				class="cbw-uploaded-skin"
				v-if="uploadedSkin"
			>
				<cbw-skin
					:skin="uploadedSkin"
					:slug="uploadedSkinSlug"
				></cbw-skin>
				<div class="cbw-uploaded-skin__cnacel">
					<div class="cbw-uploaded-skin__cnacel-heading"><?php _e( 'or', 'crocoblock-wizard' ); ?></div>
					<cx-vui-button
						@click="cancelUpload"
					>
						<span slot="label"><?php
							_e( 'Cancel and upload new one', 'crocoblock-wizard' );
						?></span>
					</cx-vui-button>
				</div>
			</div>
			<cbw-skin-uploader
				v-else
				@on-upload="setUploadedSkin"
			></cbw-skin-uploader>
		</cx-vui-tabs-panel>
	</cx-vui-tabs>
	<cx-vui-button
		tag-name="a"
		:url="backURL"
	>
		<svg slot="label" width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.67089 0L-4.76837e-07 6L5.67089 12L7 10.5938L2.65823 6L7 1.40625L5.67089 0Z" fill="#007CBA"/></svg>
		<span slot="label"><?php _e( 'Back', 'crocoblock-wizard' ); ?></span>
	</cx-vui-button>
</div>