<component
	:is="tagName"
	:class="classesList"
	:id="elementId"
	:disabled="disabled"
	v-bind="tagAtts"
	@click="handleClick"
	v-if="isVisible()"
>
	<span :class="[ this.baseClass + '__content' ]">
		<slot name="label"></slot>
	</span>
	<span v-if="loading" :class="[ this.baseClass + '__loader' ]">
		<slot name="loadingIcon">
			<span class="dashicons dashicons-admin-generic loader-icon"></span>
		</slot>
	</span>
</component>