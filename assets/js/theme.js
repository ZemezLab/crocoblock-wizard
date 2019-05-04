(function () {

	"use strict";

	Vue.component( 'cbw-install-theme', {
		template: '#cbw_install_theme',
		mixins: [ window.CBWRecursiveRequest ],
		data: function() {
			return {
				nextStep: null,
				loading: false,
				log: {},
				choices: window.CBWPageConfig.choices,
			};
		},
		methods: {
			goToNextStep: function() {

				var config = window.CBWPageConfig;

				switch ( this.nextStep ) {
					case 'parent':
						window.location = config.next_step;
						break;

					case 'child':

						self.loading = true;

						this.recursiveRequest(
							{
								key: 'get_child',
								status: 'in-progress',
								message: config.get_child,
							},
							{
								action: config.action_mask.replace( /%module%/, config.module ),
								handler: 'get_child',
							}
						);

						break;

				}

			},
			goToPrevStep: function() {
				window.location = window.CBWPageConfig.prev_step;
			}
		}
	} );

})();