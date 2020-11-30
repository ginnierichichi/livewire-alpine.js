const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

let tailwind = require('tailwindcss');

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .copy('node_modules/@fortawesome/fontawesome-free/webfonts', 'public/webfonts')
    .options({
        processCssUrls: false,
        postCss: [ tailwind('tailwind.config.js') ],
    })
    .version();



// mix.js('resources/js/app.js', 'public/js')
//     .sass('resources/sass/app.scss', 'public/css')
//     .copy('node_modules/@fortawesome/fontawesome-free/webfonts', 'public/webfonts')
//     .postCss('resources/css/app.css', 'public/css', [
//         require('postcss-import'),
//         require('tailwindcss'),
//     ])
//     .webpackConfig(require('./webpack.config'));
