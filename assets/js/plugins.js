(function () {

	"use strict";

	Vue.component( 'cbw-plugins', {
		template: '#cbw_plugins',
		data: function() {
			return {
				isUpload: window.CBWPageConfig.is_upload,
				skin: window.CBWPageConfig.skin,
				currentComponent: 'cbw-select-plugins',
			};
		},
		methods: {
		}
	} );

})();