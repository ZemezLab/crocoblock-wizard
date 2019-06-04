<div class="cbw-welcome">
	<cbw-header :title="title">
		<div class="cbw-welcome__title"><?php
			_e( 'Welcome to Crocoblock Wizard', 'crocblock-wizard' );
		?></div>
		<div class="cbw-welcome-actions">
			<div
				v-for="action in actions"
				:class="{
					'cbw-welcome-action': true,
					'cbw-welcome-action--featured': action.featured,
				}"
			>
				<div
					class="cbw-welcome-action__icon"
					v-html="action.icon"
				></div>
				<div class="cbw-welcome-action__info">
					<div
						class="cbw-welcome-action__title"
						v-html="action.title"
					></div>
					<div class="cbw-welcome-action__action">
						<a
							class="cbw-welcome-action__action-link"
							:href="action.action_url"
						>{{ action.action_label }}</a>
					</div>
				</div>
			</div>
		</div>
	</cbw-header>
	<div class="cbw-welcome-info">
		<div
			v-for="action in actions"
			:class="{
				'cbw-welcome-info__item': true,
			}"
		>
			<div
				class="cbw-welcome-info__item-title"
				v-html="action.title"
			></div>
			<div
				class="cbw-welcome-info__item-desc"
				v-html="action.desc"
			></div>
		</div>
	</div>
</div>