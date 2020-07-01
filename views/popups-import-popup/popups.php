<div class="cbw-import-popup">
	<div
		class="cbw-body__title"
		v-html="pageTitle"
	></div>
	<p class="cbw-import-popup__text"><?php
		_e( 'Pre-made popup templates can be used either with JetElements and JetPopup.<br>View demo or select template you want to import and click the ‘Start import’ button.' );
	?></p>
	<div class="cbw-popups-filters">
		<div
			class="cbw-popups-filters__list"
			v-for="filter in filters"
			:key="filter.slug"
		>
			<div
				v-for="option in filter.options"
				:key="filter.slug + option.value"
				:class="{
					'cbw-popups-filters__item': true,
					'cbw-popups-filters__item-active': isFilterActive( option.value, filter.slug ),
				}"
				@click="applyFilter( option.value, filter.slug )"
			>
				<div class="cbw-popups-filters__item-check"><svg width="12" height="9" viewBox="0 0 12 9" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.19531 4.80469L1.19531 3L0 4.19531L4.19531 9L12 1.19531L10.8047 0L4.19531 4.80469Z" fill="white"/></svg></div>
				{{ option.label }}
			</div>
		</div>
	</div>
	<div class="cbw-popups-list-wrap">
		<div class="cbw-popups-list">
			<cbw-popup
				v-for="( popup, slug ) in filteredPopups"
				@start-popup-import="startImport( $event )"
				:key="slug"
				:popup="popup"
				:slug="popup.slug"
			></cbw-popup>
		</div>
	</div>
	<div class="cbw-popups__clear-cache">
		<a href="<?php echo add_query_arg( array( 'clear_cache' => 1 ) ); ?>"><?php _e( 'Check for new popups', 'crocoblock-interactive-kit' ); ?></a>
	</div>
	<cx-vui-popup
		v-model="importing"
		ok-label="<?php _e( 'Go to popup', 'jet-engine' ) ?>"
		cancel-label="<?php _e( 'Close', 'jet-engine' ) ?>"
		:show-ok="importData.status"
		:show-cancel="importData.status"
		@on-cancel="handleCancel"
		@on-ok="goToPopup"
		body-width="400px"
	>
		<div class="cx-vui-subtitle" slot="title"><?php
			_e( 'Import popup', 'jet-engine' );
		?></div>
		<div class="cbw-import-progress" slot="content">
			<div class="cbw-import-progress__status" v-html="importData.statusString"></div>
		</div>
	</cx-vui-popup>
</div>
