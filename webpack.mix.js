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

mix.webpackConfig({
	resolve: {
		alias: {
			'sidebarStyle.less': path.resolve(__dirname, 'resources/assets/less/style.less'),
			'uiStyle.less': path.resolve(__dirname, 'resources/assets/less/style.less'),
			'fullpageStyle.less': path.resolve(__dirname, 'resources/assets/less/style.less'),
			'inputmask.dependencyLib': 'vue-inputmask/3rd/inputmask.dependencyLib',
			'vuestrapStyle.less': path.resolve(__dirname, 'node_modules/z-vuestrap2/less/style.less')
		}
	},
	output: {
		path: path.resolve(__dirname, 'public/dist'),
		publicPath: '/dist/'
	}
});

mix.js('resources/assets/js/app.js', 'public/js')
	.js('resources/assets/js/full.js', 'public/js')
	.browserSync({proxy: 'http://scout-robot.dev'});
