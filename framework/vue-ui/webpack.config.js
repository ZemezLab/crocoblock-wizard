const path = require('path');
const glob = require('glob');
const argv = require('yargs').argv;
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');

const isDevelopment = argv.mode === 'development';
const distPath = path.join(__dirname, '/assets/js/');

const config = {
	entry: {
		main: './assets/src/js/cx-vue-ui.js'
	},
	output: {
		filename: 'cx-vue-ui.js',
		path: path.resolve( __dirname, 'assets/js' )
	},
	watch: true,
	module: {
		rules: [
			{
				test: /\.js$/,
				exclude: /node_modules/,
				use: [{
					loader: 'babel-loader'
				}]
			},
			{
				test: /\.scss$/,
				exclude: /node_modules/,
				use: [
					'style-loader',
					'css-loader',
					{
						loader: 'postcss-loader',
						options: {
							plugins: function () {
								return [
									require('cssnano')({
										autoprefixer: false,
										safe: true
									})
								];
							}
						}
					},
					'sass-loader'
				]
			}
		]
	},
	optimization: {
		minimizer: [
			new UglifyJsPlugin({
				test: /\.js(\?.*)?$/i,
			})
		],
	}
};

module.exports = config;
