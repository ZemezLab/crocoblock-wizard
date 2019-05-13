(function () {

	"use strict";

	var storage = window.sessionStorage;
	storage.removeItem( 'cbw-import-content-step' );

	Vue.component( 'cbw-onboarding', {
		template: '#cbw_onboarding',
		data: function() {
			return {
				panels: window.CBWPageConfig.panels,
			};
		}
	} );

})();