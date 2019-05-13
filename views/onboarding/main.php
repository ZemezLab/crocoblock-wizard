<div class="cbw-onboarding">
	<p><?php
		_e( 'Don’t know from where to start? Let us help you!', 'crocoblock-wizard' );
	?></p>
	<div class="cbw-panels-grid">
		<div class="cbw-panel" v-for="panel in panels">
			<div class="cbw-panel__icon" v-html="panel.icon"></div>
			<div class="cbw-panel__title" v-html="panel.title"></div>
			<div class="cbw-panel__actions">
				<a
					class="cbw-panel__link"
					:href="panel.link"
					v-html="panel.link_label"
				>
			</div>
		</div>
	</div>
</div>