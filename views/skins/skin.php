<div
	class="cbw-skin"
	@mouseover="showPreview"
	@mouseleave="clearPreview"
>
	<div
		class="cbw-skin__thumb-wrap"
		v-if="skin.thumb"
	>
		<img :src="skin.thumb" alt="" class="cbw-skin__thumb">
	</div>
	<div class="cbw-skin__content">
		<div class="cbw-skin__name">{{ skin.name }}</div>
	</div>
	<transition name="fade-in">
		<div
			class="cbw-skin-preview"
			v-if="isPreview"
		>
			<div
				class="cbw-skin__thumb-wrap"
				v-if="skin.thumb"
			>
				<a :href="skin.demo">
					<img :src="skin.thumb" alt="" class="cbw-skin__thumb">
				</a>
			</div>
			<div class="cbw-skin__content">
				<div class="cbw-skin__name">{{ skin.name }}</div>
				<div class="cbw-skin__actions">
					<cx-vui-button
						:size="'mini'"
						:button-style="'accent'"
						:loading="loading"
						@click="startInstall"
					>
						<span slot="label"><?php
							_e( 'Start Install', 'crocoblock-wizard' );
						?></span>
					</cx-vui-button>
					<cx-vui-button
						v-if="skin.demo"
						:size="'mini'"
						:url="skin.demo"
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