(function () {

	"use strict";

	var storage = window.sessionStorage;

	storage.removeItem( 'cbw-import-content-step' );
	storage.removeItem( 'cbw-theme-to-install' );
	storage.removeItem( 'cbw-import-type' );

	Vue.component( 'cbw-onboarding', {
		template: '#cbw_onboarding',
		data: function() {
			return {
				panels: window.CBWPageConfig.panels,
				title: window.CBWPageConfig.title,
			};
		}
	} );

})();