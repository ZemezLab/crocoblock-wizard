<div class="cbw-import-type cbw-block">
	<div class="cbw-block__top">
		<cbw-choices
			:choices="choices"
			v-model="nextStep"
			@change="storeImportType"
		></cbw-choices>
	</div>
</div>