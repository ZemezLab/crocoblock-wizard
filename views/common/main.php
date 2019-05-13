<div
	:class="[
		'cbw-body',
		'cx-vui-panel',
		wrapperCSS
	]"
>
	<div class="cbw-body__cover" v-if="cover">
		<img :src="cover" alt="" class="cbw-body__cover-img">
	</div>
	<div class="cbw-body__content">
		<div class="cbw-body__back" v-if="defaultBackURL">
			<cx-vui-button
				tag-name="a"
				:url="defaultBackURL"
			>
				<svg slot="label" width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.67089 0L-4.76837e-07 6L5.67089 12L7 10.5938L2.65823 6L7 1.40625L5.67089 0Z" fill="#007CBA"/></svg>
				<span slot="label"><?php _e( 'Back', 'crocoblock-wizard' ); ?></span>
			</cx-vui-button>
		</div>
		<div class="cbw-body__title">{{ title }}</div>
		<component
			v-if="body"
			:is="body"
			@change-title="title = $event"
			@change-cover="cover = $event"
			@change-body="body = $event"
			@change-wrapper-css="wrapperCSS = $event"
		></component>
	</div>
</div>