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
				prevStep: {
					type: 'url',
					value: window.CBWPageConfig.prev_step,
				},
				nextStep: {
					type: 'component',
					value: 'cbw-import-content',
				},
				nextStepAllowed: false,
			};
		},
		mounted: function() {

			var importType = storage.getItem( 'cbwImortType' );

			if ( importType && ( 'append' === importType || 'replace' === importType ) ) {
				this.currentComponent = 'cbw-import-content';
				this.onComponentSwitch( this.currentComponent );
			}

		},
		methods: {
			onComponentSwitch: function( component ) {

				var newTitle, newCover;

				switch ( component ) {

					case 'cbw-select-import-type':

						newTitle = window.CBWPageConfig.title;
						newCover = window.CBWPageConfig.cover;

						this.prevStep = {
							type: 'url',
							value: window.CBWPageConfig.prev_step,
						};

						this.nextStep = {
							type: 'component',
							value: 'cbw-import-content',
						};

						storage.removeItem( 'cbwImortType' );
						this.nextStepAllowed = false;

						break;

					case 'cbw-import-content':

						newTitle = window.CBWPageConfig.import_title;
						newCover = window.CBWPageConfig.cover_import;

						this.prevStep = {
							type: 'component',
							value: 'cbw-select-import-type',
						};

						this.nextStep = {
							type: 'component',
							value: 'cbw-regenerate-thumb',
						};

						this.nextStepAllowed = false;

						break;

					case 'cbw-regenerate-thumb':
						newTitle = window.CBWPageConfig.regenerate_title;
						newCover = window.CBWPageConfig.cover_import;

						this.prevStep = {
							type: 'component',
							value: 'cbw-import-content',
						};

						this.nextStep = {
							type: 'url',
							value: window.CBWPageConfig.next_step,
						};

						this.nextStepAllowed = false;

						break;

				}

				this.currentComponent = component;

				if ( newTitle ) {
					this.$emit( 'change-title', newTitle );
				}

				if ( newCover ) {
					this.$emit( 'change-cover', newCover );
				}

			},
			goToPrevStep: function() {
				if ( 'url' === this.prevStep.type ) {
					window.location = this.prevStep.value;
				} else {
					this.currentComponent = this.prevStep.value;
					this.onComponentSwitch( this.currentComponent );
				}
			},
			goToNextStep: function() {
				if ( 'url' === this.nextStep.type ) {
					window.location = this.nextStep.value;
				} else {
					this.currentComponent = this.nextStep.value;
					this.onComponentSwitch( this.currentComponent );
				}
			},
		}
	} );

	Vue.component( 'cbw-regenerate-thumb', {
		template: '#cbw_regenerate_thumb',
		data: function() {
			return {
				progress: 0,
			};
		},
		mounted: function() {
			this.regenerateChunk( 1 );
		},
		methods: {
			regenerateChunk: function( chunk ) {

				var self = this;

				if ( ! chunk ) {
					return;
				}

				this.xhr = jQuery.ajax({
					url: ajaxurl,
					type: 'POST',
					dataType: 'json',
					data: {
						action: window.CBWPageConfig.action_mask.replace( /%module%/, window.CBWPageConfig.module ),
						handler: 'regenerate_chunk',
						skin: window.CBWPageConfig.skin,
						chunk: chunk,
						is_uploaded: window.CBWPageConfig.is_uploaded,
						nonce: window.CBWPageConfig.nonce,
					},
				}).done( function( response ) {

					if ( response.success ) {

						if ( response.data.processed ) {

							var parts = [ 'posts', 'authors', 'media', 'comments', 'terms', 'tables' ];

							parts.forEach( function( part ) {

								var newVal = response.data.processed[ part ];

								if ( newVal > self.summaryTotal[ part ] ) {
									newVal = self.summaryTotal[ part ];
								}

								self.$set( self.summaryCurrent, part, response.data.processed[ part ] );
							} );

						}

						if ( response.data.chunk ) {
							self.importChunk( response.data.chunk );
						}

						if ( response.data.complete ) {
							self.progress = response.data.complete;
						}

						if ( response.data.isLast ) {
							self.$emit( 'next-allowed', true );
						}

					} else {
						self.error = response.data.message;
					}

				}).fail( function( xhr, textStatus, error ) {
					self.error = textStatus;
				} );

			},
			getSummaryProgress: function( key ) {

				var current = this.summaryCurrent[ key ],
					total   = this.summaryTotal[ key ];

				if ( ! total ) {
					return 0;
				}

				return Math.ceil( current * 100 / total );

			}
		}
	} );

	Vue.component( 'cbw-import-content', {
		template: '#cbw_import_content',
		data: function() {
			return {
				type: '',
				nextStep: '',
				summaryInfo: window.CBWPageConfig.summary,
				progress: 0,
				total: 0,
				error: '',
				summaryTotal: {
					posts: 0,
					authors: 0,
					media: 0,
					comments: 0,
					terms: 0,
					tables: 0,
				},
				summaryCurrent: {
					posts: 0,
					authors: 0,
					media: 0,
					comments: 0,
					terms: 0,
					tables: 0,
				},
				xhr: null
			};
		},
		mounted: function() {

			var self = this;

			this.xhr = jQuery.ajax({
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

				if ( response.success ) {
					self.total        = response.data.total;
					self.summaryTotal = response.data.summary;

					self.importChunk( 1 );

				} else {
					self.error = textStatus;
				}

			}).fail( function( xhr, textStatus, error ) {
				self.error = textStatus;
			} );
		},
		methods: {
			importChunk: function( chunk ) {

				var self = this;

				if ( ! chunk ) {
					return;
				}

				this.xhr = jQuery.ajax({
					url: ajaxurl,
					type: 'POST',
					dataType: 'json',
					data: {
						action: window.CBWPageConfig.action_mask.replace( /%module%/, window.CBWPageConfig.module ),
						handler: 'import_chunk',
						skin: window.CBWPageConfig.skin,
						chunk: chunk,
						is_uploaded: window.CBWPageConfig.is_uploaded,
						nonce: window.CBWPageConfig.nonce,
					},
				}).done( function( response ) {

					if ( response.success ) {

						if ( response.data.processed ) {

							var parts = [ 'posts', 'authors', 'media', 'comments', 'terms', 'tables' ];

							parts.forEach( function( part ) {

								var newVal = response.data.processed[ part ];

								if ( newVal > self.summaryTotal[ part ] ) {
									newVal = self.summaryTotal[ part ];
								}

								self.$set( self.summaryCurrent, part, response.data.processed[ part ] );
							} );

						}

						if ( response.data.chunk ) {
							self.importChunk( response.data.chunk );
						}

						if ( response.data.complete ) {
							self.progress = response.data.complete;
						}

						if ( response.data.isLast ) {
							self.$emit( 'next-allowed', true );
						}

					} else {
						self.error = response.data.message;
					}

				}).fail( function( xhr, textStatus, error ) {
					self.error = textStatus;
				} );

			},
			getSummaryProgress: function( key ) {

				var current = this.summaryCurrent[ key ],
					total   = this.summaryTotal[ key ];

				if ( ! total ) {
					return 0;
				}

				return Math.ceil( current * 100 / total );

			}
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
			storeImportType: function( importType ) {
				this.$emit( 'next-allowed', true );
				storage.setItem( 'cbwImortType', importType );
			}
		}
	} );

})();