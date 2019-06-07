<div class="cbw-import-type cbw-block">
	<div class="cbw-body__title"><?php
		_e( 'Install plugins', 'crocoblock-wizard' );
	?></div>
	<p><?php
		_e( 'We are ready to install demo data. Do you want to append demo content to your existing content or completely rewrite it?', 'crocoblock-wizard' );
	?></p>
	<div class="cbw-block__top">
		<cbw-choices
			:choices="choices"
			v-model="nextStep"
			@change="storeImportType"
		></cbw-choices>
	</div>
</div>