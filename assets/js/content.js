(function () {

	"use strict";

	Vue.component( 'cbw-content', {
		template: '#cbw_content',
		data: function() {
			return {
				isUpload: window.CBWPageConfig.is_upload,
				skin: window.CBWPageConfig.skin,
				currentComponent: 'cbw-select-import-type',
			};
		},
		methods: {
			onComponentSwitch: function( component ) {

				var newTitle = window.CBWPageConfig.title;

				if ( 'cbw-install-plugins' === component ) {
					newTitle = window.CBWPageConfig.install_title;
				}

				this.currentComponent = component;
				this.$emit( 'change-title', newTitle );

			}
		}
	} );

	Vue.component( 'cbw-select-import-type', {
		template: '#cbw_select_type',
		data: function() {
			return {
				choices: window.CBWPageConfig.import_types,
				nextStep: '',
			};
		},
		methods: {
			goToPrevStep: function() {
				window.location = window.CBWPageConfig.prev_step;
			},
			goToNextStep: function() {
				this.$emit( 'switch-component', 'cbw-install-plugins' );
			}
		}
	} );

})();