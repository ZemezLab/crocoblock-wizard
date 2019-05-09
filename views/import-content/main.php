<div class="cbw-content">
	<component
		:is="currentComponent"
		@select-import-type="importType = $event"
		@switch-component="onComponentSwitch"
	></component>
</div>