<div class="cbw-import-type cbw-block">
	<div class="cbw-body__title"><?php
		_e( 'Demo Content Import', 'crocoblock-wizard' );
	?></div>
	<p><?php
		_e( 'We are ready to install the demo data. Do you want to append the demo content to your existing content or completely rewrite it?<br>If you want to keep your current content without any changes, please, skip this step.
', 'crocoblock-wizard' );
	?></p>
	<div class="cbw-block__top">
		<cbw-choices
			:choices="choices"
			v-model="nextStep"
			@change="storeImportType"
		></cbw-choices>
	</div>
</div>