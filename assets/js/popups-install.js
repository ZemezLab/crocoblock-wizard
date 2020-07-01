(function () {

	"use strict";

	Vue.component( 'cbw-popups', {
		template: '#cbw_popups',
		data: function() {
			return {
				popups: window.CBWPageConfig.popups,
				filters: window.CBWPageConfig.filters,
				activeFilters: {},
				pageTitle: window.CBWPageConfig.page_title,
				loading: false,
				importing: false,
				xhr: false,
				error: '',
				importData: {
					status: false,
					url: '',
					statusString: 'Importing popup templates...',
				}
			};
		},
		computed: {
			filteredPopups: function() {
				var self   = this,
					result = [];

				self.popups.forEach( function( item ) {

					var active = null,
						found  = false;

					for ( var prop in item.filters ) {

						active = self.activeFilters[ prop ];

						if ( active && active.length ) {
							for ( var i = 0; i < active.length; i++ ) {
								if ( 0 <= item.filters[ prop ].indexOf( active[ i ] ) ) {
									found = true;
									break;
								}
							}
						} else {
							found = true;
						}

						if ( found ) {
							break;
						}

					}

					if ( found ) {
						result.push( item );
					}
				} );

				return result;
			},
		},
		methods: {
			applyFilter:  function( option, filter ) {
				if ( this.isFilterActive( option, filter ) ) {
					this.activeFilters[ filter ].splice( this.activeFilters[ filter ].indexOf( option ), 1 );
				} else {
					if ( ! this.activeFilters[ filter ] ) {
						this.$set( this.activeFilters, filter, [] );
					}
					this.activeFilters[ filter ].push( option );
				}
			},
			isFilterActive: function( option, filter ) {
				if ( ! this.activeFilters[ filter ] ) {
					return false;
				}
				return ( 0 <= this.activeFilters[ filter ].indexOf( option ) );
			},
			startImport: function( slug ) {

				var self = this;

				self.importing = true;

				self.xhr = jQuery.ajax({
					url: ajaxurl,
					type: 'POST',
					dataType: 'json',
					data: {
						action: window.CBWPageConfig.action_mask.replace( /%module%/, window.CBWPageConfig.module ),
						handler: 'import_content',
						slug: slug,
						nonce: window.CBWPageConfig.nonce,
					},
				}).done( function( response ) {

					if ( ! response.success ) {
						self.error = response.data;
					} else {
						self.$set( self.importData, 'status', true );
						self.$set( self.importData, 'url', response.data );
						self.$set( self.importData, 'statusString', 'Hooray!<br>The popup has been installed' );
					}

				}).fail( function( xhr, textStatus, error ) {
					self.error = textStatus;
				} );

			},
			handleCancel: function() {
				this.importing = false;
				this.$set( this.importData, 'status', false );
				this.$set( this.importData, 'url', '' );
				this.$set( this.importData, 'statusString', 'Importing popup templates...' );
			},
			goToPopup: function() {
				this.importing = true;
				if ( this.importData.url ) {
					window.open( this.importData.url, '_blank' );
				}
			},
		}
	} );

	Vue.component( 'cbw-popup', {
		template: '#cbw_popup',
		props: {
			popup: {
				type: Object,
				default: function() {
					return {};
				}
			},
			slug: {
				type: String,
				default: '',
			},
		},
		data: function() {
			return {
				loading: false,
				isPreview: false,
				previewTimeout: null,
			};
		},
		methods: {
			clearPreview: function() {
				if ( this.previewTimeout ) {
					clearTimeout( this.previewTimeout );
					this.isPreview = false;
				}
			},
			showPreview: function() {

				var self = this;

				if ( self.previewTimeout ) {
					clearTimeout( self.previewTimeout );
				}

				self.previewTimeout = setTimeout( function() {
					self.isPreview = true;
				}, 100 );

			},
			startInstall: function() {
				this.$emit( 'start-popup-import', this.slug );

			}
		}
	} );

})();