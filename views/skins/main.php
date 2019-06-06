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
				<div
					class="cbw-uploaded-skin__thumb-wrap"
					v-if="uploadedSkin.thumb"
				>
					<a :href="uploadedSkin.demo">
						<img :src="uploadedSkin.thumb" alt="" class="cbw-uploaded-skin__thumb">
					</a>
				</div>
				<div class="cbw-uploaded-skin__content">
					<div class="cbw-uploaded-skin__name">{{ uploadedSkin.name }}</div>
					<div class="cbw-uploaded-skin__actions">
						<cx-vui-button
							:size="'mini'"
							:button-style="'accent'"
							:loading="loading"
							@click="startInstall( uploadedSkinSlug )"
						>
							<span slot="label"><?php
								_e( 'Start Install', 'crocoblock-wizard' );
							?></span>
						</cx-vui-button>
						<cx-vui-button
							v-if="uploadedSkin.demo"
							:size="'mini'"
							:url="uploadedSkin.demo"
							:tag-name="'a'"
						>
							<span slot="label"><?php
								_e( 'View Demo', 'crocoblock-wizard' );
							?></span>
						</cx-vui-button>
					</div>
				</div>
				<div class="cbw-uploaded-skin__cnacel">
					<span
						class="cbw-uploaded-skin__cnacel-link"
						@click="cancelUpload"
					>
						<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.28516 14.1922V3.42871H13.7137V14.1922C13.7137 14.6687 13.5203 15.0892 13.1334 15.4536C12.7465 15.8179 12.3 16.0001 11.7941 16.0001H4.2048C3.69885 16.0001 3.25242 15.8179 2.86551 15.4536C2.47861 15.0892 2.28516 14.6687 2.28516 14.1922Z" fill="#C92C2C"/><path d="M14.8569 1.14286V2.28571H1.14258V1.14286H4.57115L5.5606 0H10.4388L11.4283 1.14286H14.8569Z" fill="#C92C2C"/></svg>
						<?php _e( 'Cancel and upload new one', 'crocoblock-wizard' ); ?>
					</span>
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