<div class="list-table-heading">
	<div
		:class="[
			'list-table-heading__cell',
			'cell--' + slot
		]"
		v-for="slot in slots"
	>
		<slot :name="slot"></slot>
	</div>
</div>