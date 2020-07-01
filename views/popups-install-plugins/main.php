<div class="cbw-pluigns">
	<component
		:is="currentComponent"
		@update-plugins-list="pluginsToInstall = $event"
		@switch-component="onComponentSwitch"
		:plugins-to-install="pluginsToInstall"
	></component>
</div>