<div class="list-table-item">
	<div
		:class="[
			'list-table-item__cell',
			'cell--' + slot
		]"
		v-for="slot in slots"
	>
		<slot :name="slot"></slot>
	</div>
</div>