<div class="cbw-onboarding">
	<cbw-header :title="title">
		<div class="cbw-onboarding__info">
			<div class="cbw-onboarding__icon">
				<svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 4C1 2.34315 2.34315 1 4 1H52C53.6569 1 55 2.34315 55 4V14H1V4Z" fill="white" fill-opacity="0.3" stroke="white" stroke-width="2"/><circle cx="7.5" cy="7.5" r="1.5" fill="white"/><circle cx="12.5" cy="7.5" r="1.5" fill="white"/><circle cx="17.5" cy="7.5" r="1.5" fill="white"/><path d="M54 54.9V76C54 77.1 53.1 78 52 78H4C2.9 78 2 77.1 2 76V15H54V41.9H56V13H0V76C0 78.2 1.8 80 4 80H52C54.2 80 56 78.2 56 76V54.9H54Z" fill="white"/><rect x="6" y="19" width="15" height="2" rx="1" fill="white"/><rect x="6" y="23" width="8" height="2" rx="1" fill="white"/><path d="M46.4654 48.5382L47.1283 49.037L47.7409 48.4776L73.2222 25.2122L78.4241 27.9523L47.0617 64.4709L29.4427 44.2215L34.9482 39.8714L46.4654 48.5382Z" fill="white" fill-opacity="0.3" stroke="white" stroke-width="2"/></svg>
			</div>
			<div class="cbw-onboarding__title"><?php
				_e( 'Congratulations! You’re all set!', 'crocblock-wizard' );
			?></div>
			<div class="cbw-onboarding__desc"><?php
				_e( 'Choose from where you’d like to start', 'crocblock-wizard' );
			?></div>
		</div>
		<div class="cbw-panels-grid">
			<div class="cbw-panel" v-for="panel in panels">
				<div class="cbw-panel__icon" v-html="panel.icon"></div>
				<div class="cbw-panel__content">
					<div class="cbw-panel__title" v-html="panel.title"></div>
					<div class="cbw-panel__actions">
						<a
							class="cbw-panel__link"
							:href="panel.link"
						>
							{{ panel.link_label }}
							<svg width="6" height="10" viewBox="0 0 6 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.13924 0L6 5L1.13924 10L0 8.82812L3.72152 5L0 1.17188L1.13924 0Z" fill="white"/></svg>
						</a>
					</div>
				</div>
			</div>
		</div>
	</cbw-header>
</div>