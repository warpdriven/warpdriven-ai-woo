const mix = require('laravel-mix');
mix.options({
    processCssUrls: false
});
mix.sass('assets/src/sass/backend.scss', 'assets/css', [], [
    require('postcss-import'),
    require('autoprefixer'),
]).sass('assets/src/sass/backend-rtl.scss', 'assets/css/backend-rtl.css', [], [
    require('postcss-import'),
    require('rtlcss'),
    require('autoprefixer'),
]).sass('assets/src/sass/frontend.scss', 'assets/css', [], [
    require('postcss-import'),
    require('autoprefixer'),
]).sass('assets/src/sass/frontend-rtl.scss', 'assets/css/frontend-rtl.css', [], [
    require('postcss-import'),
    require('rtlcss'),
    require('autoprefixer'),
]).js('assets/src/js/backend.js', 'assets/js').vue().js('assets/src/js/frontend.js', 'assets/js').vue();