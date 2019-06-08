<div class="cbw-slides-wrap">
	<div class="cbw-slides siema">
		<div
			class="cbw-slide"
			v-for="( slide, index ) in slides"
			:key="index"
			@click="resetAutoplay"
		>
			<div
				v-if="slide.img"
				class="cbw-slide__icon"
			>
				<img
					:src="slide.img"
					alt=""
				>
			</div>
			<div
				v-if="slide.title"
				class="cbw-slide__title"
			>
				{{ slide.title }}
			</div>
			<div
				v-if="slide.desc"
				class="cbw-slide__desc"
			>
				{{ slide.desc }}
			</div>
		</div>
	</div>
</div>