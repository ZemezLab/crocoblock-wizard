<div class="cbw-progress">
	<div class="cbw-progress__bar">
		<div
			:class="{
				'cbw-progress__bar-dot':true,
				'cbw-progress__bar-dot--done': dotIsDone( n ),
			}"
			v-for="n in dots"
		></div>
	</div>
	<div class="cbw-progress__value">{{ value }}%</div>
</div>