(function () {

	"use strict";

	Vue.component( 'cbw-plugins', {
		template: '#cbw_plugins',
		data: function() {
			return {
				isUpload: window.CBWPageConfig.is_upload,
				skin: window.CBWPageConfig.skin,
				currentComponent: 'cbw-select-plugins',
				pluginsToInstall: [],
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

	Vue.component( 'cbw-install-plugins', {
		template: '#cbw_install_plugins',
		props: {
			pluginsToInstall: {
				type: Array,
				default: function() {
					return [];
				},
			},
		},
		data: function() {
			return {
				progress: 0,
			};
		},
		methods: {

		}
	} );

	Vue.component( 'cbw-select-plugins', {
		template: '#cbw_select_plugins',
		data: function() {
			return {
				selectedSkinPlugins: window.CBWPageConfig.rec_plugins,
				selectedExtraPlugins: [],
				showRec: true,
				showExtra: false,
			};
		},
		computed: {
			pluginsToInstall: function() {
				return this.selectedSkinPlugins.concat( this.selectedExtraPlugins );
			},
			skinPlugins: function() {

				var result = [];

				window.CBWPageConfig.rec_plugins.forEach( function( plugin ) {
					if ( window.CBWPageConfig.all_plugins[ plugin ] ) {
						result.push( {
							value: plugin,
							label: window.CBWPageConfig.all_plugins[ plugin ].name,
						} );
					} else {
						result.push( {
							value: plugin,
							label: plugin.replace( /-/, ' ' ),
						} );
					}

				} );

				return result;
			},
			exraPlugins: function() {

				var result = [];

				window.CBWPageConfig.extra_plugins.forEach( function( plugin ) {
					if ( window.CBWPageConfig.all_plugins[ plugin ] ) {
						result.push( {
							value: plugin,
							label: window.CBWPageConfig.all_plugins[ plugin ].name,
						} );
					} else {
						result.push( {
							value: plugin,
							label: plugin.replace( /-/, ' ' ),
						} );
					}

				} );

				return result;

			}
		},
		methods: {
			emitPluginsToInstall: function() {
				this.$emit( 'update-plugins-list', this.pluginsToInstall );
			},
			goToPrevStep: function() {
				window.location = window.CBWPageConfig.prev_step;
			},
			goToNextStep: function() {
				this.$emit( 'switch-component', 'cbw-install-plugins' );
			}
		}
	} );

})();