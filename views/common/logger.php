<div class="cbw-log">
	<div
		v-for="( item, index ) in log"
		:key="index"
		:class="[ 'cbw-log__item', 'cbw-log__item--' + item.status ]"
	>
		{{ item.message }}
	</div>
</div>