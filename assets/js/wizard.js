(function () {

	"use strict";

	Vue.component( 'cbw-choices', {
		template: '#cbw_choices',
		props: {
			value: {
				type: String,
				default: '',
			},
			choices: {
				type: Array,
				default: function() {
					return [];
				},
			}
		},
		data: function() {
			return {
				selected: false,
			};
		},
		methods: {
			makeChoice: function( choice, index ) {

				this.selected = index;

				this.$emit( 'input', choice.value );
				this.$emit( 'change', choice.value );
			},
		},
	} );

	Vue.component( 'cbw-video-popup', {
		template: '#cbw_video',
		props: {
			url: {
				type: String,
				default: '',
			},
			active: {
				type: Boolean,
				default: false,
			},
			width: {
				type: Number,
				default: 720,
			},
			height: {
				type: Number,
				default: 405,
			}
		},
		methods: {
			closePopup: function() {
				this.$emit( 'close', true );
			}
		},
	} );

	Vue.component( 'cbw-logger', {
		template: '#cbw_logger',
		props: {
			log: {
				type: Object,
				default: function() {
					return {};
				}
			},
		},
	} );

	Vue.component( 'cbw-progress', {
		template: '#cbw_progress',
		props: {
			value: {
				type: Number,
				default: 0,
			},
		},
		data: function() {
			return {
				dots: 28,
			};
		},
		methods: {
			dotIsDone: function( n ) {
				var done = ( this.value * this.dots ) / 100;
				return n <= Math.floor( done );
			},
		}
	} );

	Vue.component( 'cbw-progress-alt', {
		template: '#cbw_progress_alt',
		props: {
			value: {
				type: Number,
				default: 0,
			},
		},
	} );

	Vue.component( 'cbw-header', {
		template: '#cbw_header',
		props: {
			title: {
				type: String,
				default: '',
			},
		},
		data: function() {
			return {
				mainPage: window.CBWPageConfig.main_page,
			};
		}
	} );

	Vue.component( 'cbw-main', {
		template: '#cbw_main',
		data: function() {
			return {
				title: window.CBWPageConfig.title,
				wrapperCSS: window.CBWPageConfig.wrapper_css,
				body: window.CBWPageConfig.body,
				hasHeader: window.CBWPageConfig.has_header,
			};
		},
	} );

	new Vue({
		el: '#crocoblock_wizard',
	});

})();
