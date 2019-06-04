(function () {

	"use strict";

	Vue.component( 'cbw-welcome', {
		template: '#cbw_welcome',
		data: function() {
			return {
				actions: window.CBWPageConfig.actions,
				title: window.CBWPageConfig.title,
			};
		}
	} );

})();