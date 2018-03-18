/* eslint-disable */
var webpack = require( 'webpack' );
var path = require( 'path' );

module.exports = {
	// Load in src/main.js as our input file...
	devtool: 'source-map',
	entry: {
		'content/themes/peter-wilson-2017/assets/dist/js/theme-header': './content/themes/peter-wilson-2017/assets/src/js/theme-header',
		'content/themes/peter-wilson-2017/assets/dist/js/theme-footer': './content/themes/peter-wilson-2017/assets/src/js/theme-footer',
	},
	output: {
		path: __dirname,
		// The filename of the entry chunk as relative path inside the output.path directory.
		filename: '[name].js'
	},
	module: {
		rules: [
			{
				test: /\.jsx?$/,
				include: [
					path.resolve( __dirname, 'content' ),
				],
				use: {
					loader: 'babel-loader',
				}
			},
		],
	},
};
