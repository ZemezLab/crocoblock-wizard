(function () {

	"use strict";

	Vue.component( 'cbw-export-skin', {
		template: '#cbw_export_skin',
		data: function() {
			return {
				plugins: window.CBWPageConfig.plugins,
			};
		}
	} );

})();