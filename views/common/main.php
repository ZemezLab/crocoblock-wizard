<div
	:class="[
		'cbw-body',
		'cx-vui-panel',
		wrapperCSS
	]"
>
	<cbw-header
		v-if="hasHeader"
		:title="title"
	></cbw-header>
	<div class="cbw-body__content">
		<component
			v-if="body"
			:is="body"
			@change-title="title = $event"
			@change-body="body = $event"
			@change-wrapper-css="wrapperCSS = $event"
		></component>
	</div>
</div>