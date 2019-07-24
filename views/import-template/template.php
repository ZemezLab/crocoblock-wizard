<div
	class="cbw-skin"
	@mouseover="showPreview"
	@mouseleave="clearPreview"
>
	<div
		class="cbw-skin__thumb-wrap"
		v-if="template.thumb"
	>
		<img :src="template.thumb" alt="" class="cbw-skin__thumb">
	</div>
	<div class="cbw-skin__content">
		<div class="cbw-skin__name">{{ template.name }}</div>
	</div>
	<transition name="fade-in">
		<div
			class="cbw-skin-preview"
			v-if="isPreview"
		>
			<div
				class="cbw-skin__thumb-wrap"
				v-if="template.thumb"
			>
				<a :href="template.demo">
					<img :src="template.thumb" alt="" class="cbw-skin__thumb">
				</a>
			</div>
			<div class="cbw-skin__content">
				<div class="cbw-skin__name">{{ template.name }}</div>
				<div class="cbw-skin__choices">
					<cx-vui-radio
						v-model="importType"
						:options-list="[
							{
								value: 'jet',
								label: '<?php _e( 'Jet Plugins', 'crocoblock-wizard' ); ?>',
							},
							{
								value: 'pro',
								label: '<?php _e( 'Elementor PRO', 'crocoblock-wizard' ); ?>',
							}
						]"
					></cx-vui-radio>
				</div>
				<div class="cbw-skin__actions">
					<cx-vui-button
						:size="'mini'"
						:button-style="'accent'"
						:loading="loading"
						:disabled="!importType"
						@click="startInstall"
					>
						<span slot="label"><?php
							_e( 'Start Installation', 'crocoblock-wizard' );
						?></span>
					</cx-vui-button>
					<cx-vui-button
						v-if="template.demo"
						:size="'mini'"
						:url="template.demo"
						:tag-name="'a'"
					>
						<span slot="label"><?php
							_e( 'View Demo', 'crocoblock-wizard' );
						?></span>
					</cx-vui-button>
				</div>
			</div>
		</div>
	</transition>
</div>