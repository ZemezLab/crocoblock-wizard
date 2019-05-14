(function () {

	"use strict";

	Vue.component( 'cbw-export-skin', {
		template: '#cbw_export_skin',
		data: function() {
			return {
				plugins: window.CBWPageConfig.plugins,
				exportSettings: {
					only_xml: false,
					skin_name: '',
					demo_url: '',
					thumb_url: '',
				},
				exportPlugins: {},
				loading: false,
				error: false,
				errorMessage: false,
			};
		},
		created: function() {

			var self = this;

			if ( self.plugins.length ) {
				self.plugins.forEach( function( plugin ) {
					self.$set( self.exportPlugins, plugin.slug, {
						name: plugin.name,
						include: false,
					} );
				} );
			}
		},
		methods: {
			exportSkin: function() {

				var self = this;

				self.loading = true;

				jQuery.ajax({
					url: ajaxurl,
					type: 'POST',
					dataType: 'json',
					data: {
						action: window.CBWPageConfig.action_mask.replace( /%module%/, window.CBWPageConfig.module ),
						handler: 'export_skin',
						settings: self.exportSettings,
						plugins: self.exportPlugins,
						nonce: window.CBWPageConfig.nonce,
					},
				}).done( function( response ) {

					if ( ! response ) {
						self.error        = true;
						self.errorMessage = 'Empty response';
						self.loading = false;
						return;
					}

					if ( ! response.success ) {
						self.error        = true;
						self.errorMessage = response.data.message;
						self.loading      = false;
					} else {

						window.location = response.data.redirect;
						self.loading    = false;

					}

				} ).fail( function( xhr, textStatus, error ) {

					self.loading      = false;
					self.error        = true;
					self.errorMessage = error;

				} );

			}
		}
	} );

})();