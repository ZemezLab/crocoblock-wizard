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
		<component v-if="body" :is="body"></component>
	</div>
</div>