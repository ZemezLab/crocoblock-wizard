const path = require('path');
const glob = require('glob');
const argv = require('yargs').argv;
const TerserPlugin = require("terser-webpack-plugin");

const isDevelopment = argv.mode === 'development';
const distPath = path.join(__dirname, '/assets/js/');

const config = {
	entry: {
		'cx-vue-ui': './assets/src/js/cx-vue-ui.js',
		'cx-vue-ui-components': './assets/src/js/cx-vue-ui-components.js',
	},
	output: {
		filename: '[name].js',
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
					{
						loader: 'css-loader',
						options: {
							importLoaders: 1,
							modules: {
								mode: 'icss',
							},
						}
					},
					'sass-loader',
					{
						loader: 'postcss-loader',
						options: {
							postcssOptions: {

							},
						}
					},
				]
			}
		]
	},
	optimization: {
		minimize: true,
		minimizer: [
			new TerserPlugin({} )
		],
	}
};

module.exports = config;
