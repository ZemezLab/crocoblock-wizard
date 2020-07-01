<div class="cbw-pluigns">
	<div class="cbw-select-plugins">
		<div class="cbw-block">
			<div class="cbw-body__title"><?php
				_e( 'Required plugins are missing', 'croco-ik' );
			?></div>
			<div class="cbw-block__top">
				<p><?php
					_e( 'Please install these plugins before continue', 'croco-ik' );

				?></p>
				<ul>
					<li v-for="plugin in pluginsList" :key="plugin.value">{{ plugin.label }}</li>
				</ul>
			</div>
		</div>
		<div class="cbw-footer">
			<cx-vui-button
				@click="goToPrevStep"
			>
				<svg slot="label" width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.67089 0L-4.76837e-07 6L5.67089 12L7 10.5938L2.65823 6L7 1.40625L5.67089 0Z" fill="#007CBA"/></svg>
				<span slot="label"><?php _e( 'Back', 'croco-ik' ); ?></span>
			</cx-vui-button>
			<cx-vui-button
				@click="skipPlugins"
			>
				<span slot="label"><?php _e( 'Skip', 'croco-ik' ); ?></span>
				<svg slot="label" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.32911 1L14 7L8.32911 13L7 11.5938L11.3418 7L7 2.40625L8.32911 1Z" fill="#007CBA"/><path d="M1.32911 1L7 7L1.32911 13L0 11.5938L4.34177 7L0 2.40625L1.32911 1Z" fill="#007CBA"/></svg>
			</cx-vui-button>
		</div>
	</div>
</div>