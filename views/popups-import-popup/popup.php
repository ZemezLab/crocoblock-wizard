<div
	class="cbw-popup"
	@mouseover="showPreview"
	@mouseleave="clearPreview"
>
	<div
		class="cbw-popup__thumb-wrap"
		v-if="popup.thumb"
	>
		<img :src="popup.thumb" alt="" class="cbw-popup__thumb">
	</div>
	<div class="cbw-popup__content">
		<div class="cbw-popup__name">{{ popup.name }}</div>
	</div>
	<transition name="fade-in">
		<div
			class="cbw-popup-preview"
			v-if="isPreview"
		>
			<div
				class="cbw-popup__thumb-wrap"
				v-if="popup.thumb"
			>
				<a :href="popup.demo">
					<img :src="popup.thumb" alt="" class="cbw-popup__thumb">
				</a>
			</div>
			<div class="cbw-popup__content">
				<div class="cbw-popup__name">{{ popup.name }}</div>
				<div class="cbw-popup__actions">
					<cx-vui-button
						:size="'mini'"
						:button-style="'accent'"
						:loading="loading"
						@click="startInstall"
					>
						<span slot="label"><?php
							_e( 'Start Import', 'croco-ik' );
						?></span>
					</cx-vui-button>
					<a
						v-if="popup.demo"
						:href="popup.demo"
						class="cx-vui-button cx-vui-button--style-default cx-vui-button--size-mini"
						target="_blank"
					>
						<span class="cx-vui-button__content"><?php
							_e( 'View Demo', 'croco-ik' );
						?></span>
					</a>
				</div>
			</div>
		</div>
	</transition>
</div>