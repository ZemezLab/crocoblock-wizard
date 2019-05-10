(function () {

	"use strict";

	var storage = window.sessionStorage;

	Vue.component( 'cbw-content', {
		template: '#cbw_content',
		data: function() {
			return {
				isUpload: window.CBWPageConfig.is_upload,
				skin: window.CBWPageConfig.skin,
				currentComponent: 'cbw-select-import-type',
			};
		},
		mounted: function() {

			var importType = storage.getItem( 'cbwImortType' );

			if ( importType && ( 'append' === importType || 'replace' === importType ) ) {
				this.currentComponent = 'cbw-import-content';
				this.$emit( 'change-title', window.CBWPageConfig.import_title );
				this.$emit( 'change-cover', window.CBWPageConfig.cover_import );
			}

		},
		methods: {
			onComponentSwitch: function( component ) {

				var newTitle, newCover;

				switch ( component ) {

					case 'cbw-select-import-type':
						newTitle = window.CBWPageConfig.title;
						newCover = window.CBWPageConfig.cover;
						break;

					case 'cbw-import-content':
						newTitle = window.CBWPageConfig.import_title;
						newCover = window.CBWPageConfig.cover_import;
						break;

					case 'cbw-select-regenerate-thumb':
						newTitle = window.CBWPageConfig.regenerate_title;
						newCover = window.CBWPageConfig.cover_import;
						break;

				}

				this.currentComponent = component;

				if ( newTitle ) {
					this.$emit( 'change-title', newTitle );
				}

				if ( newCover ) {
					this.$emit( 'change-cover', newCover );
				}

			}
		}
	} );

	Vue.component( 'cbw-import-content', {
		template: '#cbw_import_content',
		data: function() {
			return {
				type: '',
				nextStep: '',
			};
		},
		mounted: function() {
			jQuery.ajax({
				url: ajaxurl,
				type: 'GET',
				dataType: 'json',
				data: {
					action: window.CBWPageConfig.action_mask.replace( /%module%/, window.CBWPageConfig.module ),
					handler: 'get_import_info',
					skin: window.CBWPageConfig.skin,
					is_uploaded: window.CBWPageConfig.is_uploaded,
					nonce: window.CBWPageConfig.nonce,
				},
			}).done( function( response ) {

			}).fail( function( xhr, textStatus, error ) {

			} );
		},
		methods: {

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
				this.$emit( 'switch-component', 'cbw-import-content' );
			},
			storeImportType: function( importType ) {
				storage.setItem( 'cbwImortType', importType );
			}
		}
	} );

})();