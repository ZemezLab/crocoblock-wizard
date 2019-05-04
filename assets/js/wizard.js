(function () {

	"use strict";

	Vue.config.devtools = true;

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
			},
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

	Vue.component( 'cbw-main', {
		template: '#cbw_main',
		data: function() {
			return {
				title: window.CBWPageConfig.title,
				cover: window.CBWPageConfig.cover,
				wrapperCSS: window.CBWPageConfig.wrapper_css,
				body: window.CBWPageConfig.body,
			};
		}
	} );

	new Vue({
		el: '#crocoblock_wizard',
	});

})();
