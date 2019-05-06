(function () {

	"use strict";

	Vue.component( 'cbw-skins', {
		template: '#cbw_skins',
		data: function() {
			return {
				skinsByTypes: window.CBWPageConfig.skins_by_types,
				allowedTypes: window.CBWPageConfig.allowed_types,
			};
		},
		methods: {
		}
	} );

	Vue.component( 'cbw-skin', {
		template: '#cbw_skin',
		props: {
			skin: {
				type: Object,
				default: function() {
					return {};
				}
			}
		},
		methods: {
		}
	} );

	Vue.component( 'cbw-skin-uploader', {
		template: '#cbw_skin_uploader',
		data: function() {
			return {
				isActive: false,
				error: false,
			};
		},
		methods: {
			onDrop: function( e ) {

				this.isActive = false;
				this.error    = false;

				if ( ! e.dataTransfer.files.length ) {
					return;
				}

				this.uploadFile( e.dataTransfer.files );

			},
			onInputChange: function( e ) {

				this.error = false;

				if ( ! e.target.files ) {
					return;
				}

				this.uploadFile( files );

			},
			uploadFile: function( files ) {

				var self = this,
					file,
					formData,
					xhr;

				if ( 1 < files.length ) {
					this.error = window.CBWPageConfig.upload_errors.limit;
					return;
				}

				file = files[0];

				if ( 'application/zip' !== file.type ) {
					this.error = window.CBWPageConfig.upload_errors.type;
				}

				formData = new FormData();
				formData.append( '_skin', file );
				formData.append( 'action', window.CBWPageConfig.upload_hook.action );
				formData.append( 'handler', window.CBWPageConfig.upload_hook.handler );
				formData.append( 'nonce', window.CBWPageConfig.nonce );

				xhr = new XMLHttpRequest();

				xhr.open( 'POST', ajaxurl, true );

				xhr.onload = function( e ) {
					if ( xhr.status == 200 ) {
						console.log( e );
					} else {
						self.error = xhr.status;
					}
				};

				xhr.send( formData );

			}
		}
	} );

})();