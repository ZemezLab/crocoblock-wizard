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