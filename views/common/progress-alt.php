<div
	:class="{
		'cbw-progress-alt': true,
		'cbw-progress-alt--complete': value >= 100,
	}"
>
	<div class="cbw-progress-alt__bar">
		<div
			class="cbw-progress-alt__done"
			:style="{
				width: value + '%',
			 }"
		></div>
	</div>

</div>