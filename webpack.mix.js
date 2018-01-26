let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.config.resourceRoot = 'dist/';

    mix.webpackConfig({
        module: {
            rules: [{
                test: /\.js?$/,
                exclude: /(bower_components)/,
                use: [{
                    loader: 'babel-loader',
                    options: mix.config.babel()
                }]
            }]
        },
		output: {
			path: path.resolve(__dirname, 'public/dist'),
			publicPath: '/dist/'
		}
    });

mix.js('resources/assets/js/app.js', 'public/js')
	.js('resources/assets/js/full.js', 'public/js');
