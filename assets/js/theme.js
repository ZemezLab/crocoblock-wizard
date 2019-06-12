(function () {

	"use strict";

	var storage = window.sessionStorage;

	Vue.component( 'cbw-install-theme', {
		template: '#cbw_install_theme',
		mixins: [ window.CBWRecursiveRequest ],
		data: function() {
			return {
				nextStep: null,
				loading: false,
				theme: false,
				prev: window.CBWPageConfig.install.prev,
				log: {},
				choices: window.CBWPageConfig.install.choices,
			};
		},
		mounted: function() {
			this.$emit( 'change-wrapper-css', 'theme-page install-theme' );
			this.theme = storage.getItem( 'cbw-theme-to-install' );
		},
		methods: {
			goToNextStep: function() {

				var config = window.CBWPageConfig;

				this.loading = true;

				switch ( this.nextStep ) {
					case 'parent':

						this.recursiveRequest(
							{
								key: 'get_parent',
								status: 'in-progress',
								message: config.install.get_parent,
								theme: this.theme,
							},
							{
								action: config.action_mask.replace( /%module%/, config.module ),
								handler: 'install_parent',
								child: false,
								theme: this.theme,
							}
						);

						break;

					case 'child':

						this.recursiveRequest(
							{
								key: 'get_parent',
								status: 'in-progress',
								message: config.install.get_parent,
							},
							{
								action: window.CBWPageConfig.action_mask.replace( /%module%/, config.module ),
								handler: 'install_parent',
								child: true,
							}
						);

						break;

				}

			},
			goToPrevStep: function() {
				storage.removeItem( 'cbw-theme-to-install' );
				this.$emit( 'change-body', 'cbw-select-theme' );
			}
		}
	} );

	Vue.component( 'cbw-select-theme', {
		template: '#cbw_select_theme',
		data: function() {
			return {
				nextCurrent: window.CBWPageConfig.select.next_step.current,
				nextTheme: window.CBWPageConfig.select.next_step.selected,
				themes: window.CBWPageConfig.select.themes,
			};
		},
		mounted: function() {
			this.$emit( 'change-wrapper-css', 'theme-page select-theme' );
		},
		created: function() {

			var theme = storage.getItem( 'cbw-theme-to-install' );

			if ( theme && this.themes[ theme ] ) {
				this.$emit( 'change-body', this.nextTheme );
			}

		},
		methods: {
			startInstall: function( theme ) {
				storage.setItem( 'cbw-theme-to-install', theme );
				this.$emit( 'change-body', this.nextTheme );
			},
			goToPrev: function() {
				window.location = window.CBWPageConfig.select.prev;
			},
		}
	} );

})();