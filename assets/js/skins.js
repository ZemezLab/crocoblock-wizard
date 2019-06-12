(function () {

	"use strict";

	Vue.component( 'cbw-skins', {
		template: '#cbw_skins',
		data: function() {
			return {
				skinsByTypes: window.CBWPageConfig.skins_by_types,
				allowedTypes: window.CBWPageConfig.allowed_types,
				uploadedSkin: false,
				uploadedSkinSlug: false,
				action: window.CBWPageConfig.action,
				pageTitle: window.CBWPageConfig.page_title,
				firstTab: window.CBWPageConfig.first_tab,
				loading: false,
				backURL: window.CBWPageConfig.default_back,
			};
		},
		methods: {
			cancelUpload: function() {

				var slug = this.uploadedSkinSlug;

				this.uploadedSkin     = false;
				this.uploadedSkinSlug = false;

				jQuery.ajax({
					url: ajaxurl,
					type: 'POST',
					dataType: 'json',
					data: {
						action: window.CBWPageConfig.action_mask.replace( /%module%/, window.CBWPageConfig.module ),
						handler: 'delete_uploaded_skin',
						slug: slug,
						nonce: window.CBWPageConfig.nonce,
					},
				});
			},
			startInstall: function( skin ) {

				this.loading = true;

				jQuery.ajax({
					url: ajaxurl,
					type: 'POST',
					dataType: 'json',
					data: {
						action: window.CBWPageConfig.action_mask.replace( /%module%/, window.CBWPageConfig.module ),
						handler: 'prepare_skin_installation',
						slug: skin,
						is_uploaded: true,
						nonce: window.CBWPageConfig.nonce,
					},
				}).done( function( response ) {
					if ( ! response.success ) {
						alert( response.data.message );
					} else {
						window.location = response.data.redirect;
					}
				} );
			},
			setUploadedSkin: function( skinData ) {

				this.uploadedSkin = {
					name: skinData.name,
					thumb: skinData.thumbnail,
					demo: skinData.demo,
					is_uploaded: true,
				};

				this.uploadedSkinSlug = skinData.slug;
			},
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

				var isUploaded = false;

				this.loading = true;

				if ( this.skin.is_uploaded ) {
					isUploaded = true;
				}

				jQuery.ajax({
					url: ajaxurl,
					type: 'POST',
					dataType: 'json',
					data: {
						action: window.CBWPageConfig.action_mask.replace( /%module%/, window.CBWPageConfig.module ),
						handler: 'prepare_skin_installation',
						slug: this.slug,
						is_uploaded: isUploaded,
						nonce: window.CBWPageConfig.nonce,
					},
				}).done( function( response ) {
					if ( ! response.success ) {
						alert( response.data.message );
					} else {
						window.location = response.data.redirect;
					}
				} );

			}
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

				this.uploadFile( e.target.files );

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

				xhr.onload = function( e, r ) {
					if ( xhr.status == 200 ) {
						var response = e.currentTarget.response;
						response = JSON.parse( response );

						if ( ! response.success ) {
							self.error = response.data.message;
							return;
						} else {
							self.$emit( 'on-upload', response.data );
						}

					} else {
						self.error = xhr.status;
					}
				};

				xhr.send( formData );

			}
		}
	} );

})();