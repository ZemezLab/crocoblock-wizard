(function () {

	"use strict";

	Vue.component( 'cbw-free-templates', {
		template: '#cbw_free_templates',
		data: function() {
			return {
				activeTab: 'home-pages',
				items: window.CBWPageConfig.templates,
				tabs: window.CBWPageConfig.tabs,
			};
		},
		methods: {
			templatesByTabs: function( tab ) {

				var result = {};

				for ( var slug in this.items ) {
					if ( this.items[ slug ].tab === tab ) {
						result[ slug ] = this.items[ slug ];
					}
				}

				return result;
			}
		}
	} );

	Vue.component( 'cbw-template', {
		template: '#cbw_template',
		props: {
			template: {
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
				importType: false,
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
			}
		}
	} );

})();