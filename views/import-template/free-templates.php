<div>
	<cx-vui-tabs
		:invert="true"
		:in-panel="true"
		:value="activeTab"
	>
		<cx-vui-tabs-panel
			v-for="( tabLabel, tabSlug ) in tabs"
			:name="tabSlug"
			:label="tabLabel"
			:key="tabSlug"
		>
			<div class="cbw-skins-list">
				<cbw-template
					v-for="( template, slug ) in templatesByTabs( tabSlug )"
					:template="template"
					:slug="slug"
					:key="tabSlug + slug"
				></cbw-template>
			</div>
		</cx-vui-tabs-panel>
	</cx-vui-tabs>
	<cx-vui-button
	>
		<svg slot="label" width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.67089 0L-4.76837e-07 6L5.67089 12L7 10.5938L2.65823 6L7 1.40625L5.67089 0Z" fill="#007CBA"/></svg>
		<span slot="label"><?php _e( 'Back', 'crocoblock-wizard' ); ?></span>
	</cx-vui-button>
</div>