<div
	:class="[ 'cbw-skin-' + action ]"
>
	<div
		class="cbw-body__title"
		v-html="pageTitle"
	></div>
	<p><?php
		_e( 'Each skin comes with custom demo content and predefined set of plugins. Depending upon the selected skin the wizard will install required plugins and some demo post and pages.', 'crocoblock-wizard' );
	?></p>
	<cx-vui-tabs
		:invert="true"
		:in-panel="true"
		:value="firstTab"
	>
		<cx-vui-tabs-panel
			v-for="( typeLabel, typeSlug ) in allowedTypes"
			:name="typeSlug"
			:label="typeLabel"
			:key="typeSlug"
			v-if="'select' === action"
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
				>
					<a
						:href="uploadedSkin.demo"
						v-if="uploadedSkin.thumb"
					>
						<img :src="uploadedSkin.thumb" alt="" class="cbw-uploaded-skin__thumb">
					</a>
					<svg
						v-else
						width="306"
						height="229"
						viewBox="0 0 306 229"
						fill="none"
						xmlns="http://www.w3.org/2000/svg"
					>
						<path d="M1 4C1 2.34315 2.34315 1 4 1H302C303.657 1 305 2.34315 305 4V225C305 226.657 303.657 228 302 228H4C2.34315 228 1 226.657 1 225V4Z" fill="#EDF6FA" stroke="#80BDDC" stroke-width="2"/><path d="M74 30C74 28.3431 75.3431 27 77 27H229C230.657 27 232 28.3431 232 30V46H74V30Z" fill="white" stroke="#80BDDC" stroke-width="2"/><path d="M74 46H232V228H74V46Z" fill="white" stroke="#80BDDC" stroke-width="2"/><path d="M84 57C84 56.4477 84.4477 56 85 56H221C221.552 56 222 56.4477 222 57V107C222 107.552 221.552 108 221 108H85C84.4477 108 84 107.552 84 107V57Z" fill="#EDF6FA" stroke="#80BDDC" stroke-width="2"/><circle cx="140.5" cy="72.5" r="6.5" fill="white" stroke="#80BDDC" stroke-width="2"/><path d="M144.799 99.4604L145.461 99.9589L146.074 99.4008L169.126 78.4051L196.701 108H109.066L132.968 90.5525L144.799 99.4604Z" fill="white" stroke="#80BDDC" stroke-width="2"/><path d="M84 116C84 115.448 84.4477 115 85 115H124C124.552 115 125 115.448 125 116V166C125 166.552 124.552 167 124 167H85C84.4477 167 84 166.552 84 166V116Z" fill="white" stroke="#80BDDC" stroke-width="2"/><path d="M132 116C132 115.448 132.448 115 133 115H172C172.552 115 173 115.448 173 116V166C173 166.552 172.552 167 172 167H133C132.448 167 132 166.552 132 166V116Z" fill="white" stroke="#80BDDC" stroke-width="2"/><rect x="132" y="174" width="90" height="44" rx="1" fill="#EDF6FA" stroke="#80BDDC" stroke-width="2"/><rect x="180" y="114" width="43" height="2" rx="1" fill="#80BDDC"/><rect x="180" y="120" width="43" height="2" rx="1" fill="#80BDDC"/><rect x="180" y="126" width="43" height="2" rx="1" fill="#80BDDC"/><rect x="180" y="132" width="43" height="2" rx="1" fill="#80BDDC"/><path d="M180 139C180 138.448 180.448 138 181 138H204C204.552 138 205 138.448 205 139C205 139.552 204.552 140 204 140H181C180.448 140 180 139.552 180 139Z" fill="#80BDDC"/><circle cx="83.5" cy="36.5" r="1.5" fill="#80BDDC"/><circle cx="89.5" cy="36.5" r="1.5" fill="#80BDDC"/><circle cx="95.5" cy="36.5" r="1.5" fill="#80BDDC"/>
					</svg>
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