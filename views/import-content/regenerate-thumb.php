<div>
	<cbw-progress :value="progress"></cbw-progress>
	<p v-if="!progress"><?php
		_e( 'Starting process, please wait few seconds...', 'crcoblock-wizard' );
	?></p>
</div>