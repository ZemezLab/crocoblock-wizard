<div class="cbw-regenerate">
	<div class="cbw-body__title"><?php
		_e( 'Regenerate thumbnail', 'crocoblock-wizard' );
	?></div>
	<cbw-progress :value="progress"></cbw-progress>
	<p v-if="!progress"><?php
		_e( 'Starting process, please wait few seconds...', 'crcoblock-wizard' );
	?></p>
	<cbw-slides></cbw-slides>
</div>