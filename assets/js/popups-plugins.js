(function () {

	"use strict";

	Vue.component( 'cbw-plugins', {
		template: '#cbw_plugins',
		data: function() {
			return {
				isUpload: window.CBWPageConfig.is_uploaded,
				skin: window.CBWPageConfig.skin,
				currentComponent: 'cbw-select-plugins',
				pluginsToInstall: window.CBWPageConfig.rec_plugins,
			};
		},
		methods: {
			onComponentSwitch: function( component ) {

				var newTitle = window.CBWPageConfig.title;

				if ( 'cbw-install-plugins' === component ) {
					this.$emit( 'change-wrapper-css', 'plugins-page install-step' );
				} else {
					this.$emit( 'change-wrapper-css', 'plugins-page' );
				}

				this.currentComponent = component;

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
		mounted: function() {

			if ( ! this.pluginsToInstall.length ) {
				return;
			}

			this.installPlugin();

		},
		data: function() {
			return {
				progress: 0,
				installedPlugins: {},
				done: false,
				loading: false,
			};
		},
		methods: {
			goToNextStep: function() {
				this.loading    = true;
				window.location = window.CBWPageConfig.next_step;
			},
			goToPrevStep: function() {
				this.$emit( 'switch-component', 'cbw-select-plugins' );
			},
			itemClasses: function( plugin ) {
				var classes = [ 'cbw-plugin', 'cbw-plugin--' + plugin.status ];

				if ( ! plugin.collapsed ) {
					classes.push( 'cbw-plugin--expanded' );
				}

				return classes;

			},
			installPlugin: function( index ) {

				var self = this;

				if ( ! index ) {
					index = 0;
				}

				if ( ! this.pluginsToInstall[ index ] ) {
					return;
				}

				var plugin     = this.pluginsToInstall[ index ],
					pluginData = {
						name: self.getPluginName( plugin ),
						log: '',
						status: 'in-progress',
						collapsed: true,
					};

				self.$set( self.installedPlugins, plugin, pluginData );

				jQuery.ajax({
					url: ajaxurl,
					type: 'POST',
					dataType: 'json',
					data: {
						action: window.CBWPageConfig.action_mask.replace( /%module%/, window.CBWPageConfig.module ),
						handler: 'install_plugin',
						plugin: plugin,
						skin: window.CBWPageConfig.skin,
						is_uploaded: window.CBWPageConfig.is_uploaded,
						nonce: window.CBWPageConfig.nonce,
					},
				}).done( function( response ) {

					pluginData['log'] = response.data.message;

					if ( response.success ) {
						pluginData['status'] = 'success';
					} else {
						pluginData['status'] = 'error';
					}

					self.$set( self.installedPlugins, plugin, pluginData );

					self.goToNext( index );

				}).fail( function( xhr, textStatus, error ) {

					pluginData['status'] = 'error';
					pluginData['log']    = error;

					self.$set( self.installedPlugins, plugin, pluginData );

					self.goToNext( index );

				} );

			},
			goToNext: function( index ) {

				this.updateProgress( index );

				if ( index + 1 < this.pluginsToInstall.length ) {
					this.installPlugin( index + 1 );
				} else {

					/**
					 * By default nex step after plugins is demo content, so ensure it will be stqarted correctlly
					 */
					window.sessionStorage.removeItem( 'cbw-import-type' );
					window.sessionStorage.removeItem( 'cbw-import-content-step' );

					window.location = window.CBWPageConfig.next_step;
					this.done       = true;
				}

			},
			updateProgress: function( index ) {

				var result = ( index + 1 ) / this.pluginsToInstall.length;

				result = result * 100;
				result = Math.ceil( result );

				if ( 100 < result ) {
					result = 100;
				}

				this.progress = result;

			},
			getPluginName: function( plugin ) {
				if ( window.CBWPageConfig.plugin_names[ plugin ] ) {
					return window.CBWPageConfig.plugin_names[ plugin ];
				} else {
					return plugin.replace( /-/, ' ' );
				}
			}
		}
	} );

	Vue.component( 'cbw-select-plugins', {
		template: '#cbw_select_plugins',
		data: function() {
			return {
				pluginsList: window.CBWPageConfig.plugins,
				selectedPlugins: [],
			};
		},
		mounted: function() {
			var self = this;

			self.pluginsList.forEach( function( plugin ) {
				self.selectedPlugins.push( plugin.value );
			} );

			self.$emit( 'update-plugins-list', self.selectedPlugins );
		},
		methods: {

			skipPlugins: function() {

				/**
				 * By default nex step after plugins is demo content, so ensure it will be started correctlly
				 */
				window.sessionStorage.removeItem( 'cbw-import-type' );
				window.sessionStorage.removeItem( 'cbw-import-content-step' );

				window.location = window.CBWPageConfig.next_step;
			},
			emitPluginsToInstall: function() {
				this.$emit( 'update-plugins-list', this.selectedPlugins );
			},
			goToPrevStep: function() {
				window.location = window.CBWPageConfig.prev_step;
			},
			goToNextStep: function() {
				if ( ! this.selectedPlugins.length ) {
					window.location = window.CBWPageConfig.next_step;
				} else {
					this.$emit( 'switch-component', 'cbw-install-plugins' );
				}
			}
		}
	} );

	Vue.component( 'cbw-plugins-info', {
		template: '#cbw_plugins_info',
		data: function() {
			return {
				pluginsList: window.CBWPageConfig.plugins,
			};
		},
		methods: {

			skipPlugins: function() {

				/**
				 * By default nex step after plugins is demo content, so ensure it will be started correctlly
				 */
				window.sessionStorage.removeItem( 'cbw-import-type' );
				window.sessionStorage.removeItem( 'cbw-import-content-step' );

				window.location = window.CBWPageConfig.next_step;
			},
			goToPrevStep: function() {
				window.location = window.CBWPageConfig.prev_step;
			}
		}
	} );

})();