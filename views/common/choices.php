<div class="cbw-choices">
	<div
		:class="{
			'cbw-choice': true,
			'cbw-choice--selected': index === selected,
		}"
		v-for="( choice, index ) in choices"
		@click="makeChoice( choice, index )"
	>
		<div class="cbw-choice__mark"></div>
		<div class="cbw-choice__content">
			<div class="cbw-choice__label" v-html="choice.label"></div>
			<div class="cbw-choice__desc" v-html="choice.description"></div>
		</div>
	</div>
</div>