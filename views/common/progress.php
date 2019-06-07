<div class="cbw-progress">
	<div class="cbw-progress__bar">
		<div
			class="cbw-progress__done"
			:style="{
				width: value + '%',
			 }"
		></div>
	</div>
	<div class="cbw-progress__value">{{ value }}%</div>
</div>