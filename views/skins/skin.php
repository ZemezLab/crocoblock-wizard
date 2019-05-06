<div class="cbw-skin">
	<div class="cbw-skin__thumb-wrap">
		<a :href="skin.demo">
			<img :src="skin.thumb" alt="" class="cbw-skin__thumb">
		</a>
	</div>
	<div class="cbw-skin__content">
		<div class="cbw-skin__name">{{ skin.name }}</div>
		<div class="cbw-skin__actions">
			<cx-vui-button
				:size="'mini'"
				:button-style="'accent'"
			>
				<span slot="label"><?php
					_e( 'Start Install', 'crocoblock-wizard' );
				?></span>
			</cx-vui-button>
			<cx-vui-button
				:size="'mini'"
				:href="skin.demo"
			>
				<span slot="label"><?php
					_e( 'View Demo', 'crocoblock-wizard' );
				?></span>
			</cx-vui-button>
		</div>
	</div>
</div>