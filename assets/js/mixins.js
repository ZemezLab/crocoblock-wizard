(function () {

	"use strict";

	window.CBWRecursiveRequest = {

		methods: {
			recursiveRequest: function( message, data ) {

				var self = this;

				self.$set( self.log, message.key, {
					status: message.status,
					message: message.message,
				} );

				data.nonce = window.CBWPageConfig.nonce;

				jQuery.ajax({
					url: ajaxurl,
					type: 'POST',
					dataType: 'json',
					data: data,
				}).done( function( response ) {

					if ( response.success ) {

						self.$set( self.log, message.key, {
							status: 'done',
							message: message.message,
						} );

						if ( response.data.doNext ) {

							self.recursiveRequest( {
								key: response.data.nextRequest.handler,
								status: 'in-progress',
								message: response.data.message,
							}, response.data.nextRequest );

						} else {

							self.loading = false;

							self.$set( self.log, 'last_step', {
								status: 'in-progress',
								message: response.data.message,
							} );

							if ( response.data.redirect ) {
								window.location = response.data.redirect;
							}

						}

					} else {

						self.loading = false;

						self.$set( self.log, 'break', {
							status: 'error',
							message: response.data.message,
						} );

					}

				}).fail( function( xhr, textStatus, error ) {

					self.loading = false;

					self.$set( self.log, 'break', {
						status: 'error',
						message: error,
					} );

				} );

			},
		}

	};

})();