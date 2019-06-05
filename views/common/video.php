<div
	class="cbw-video-popup"
	v-if="active"
>
	<div class="cbw-video-popup__overlay" @click="closePopup"></div>
	<div class="cbw-video-popup__body">
		<iframe
			:width="width"
			:height="height"
			:src="url"
			frameborder="0"
			allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
			allowfullscreen
		></iframe>
	</div>
</div>