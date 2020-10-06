const mix = require('laravel-mix');

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

mix.js('resources/js/app.js', 'public/assets/js/all.js')
    .sass('resources/sass/app.scss', 'public/assets/css/all.css');
mix.styles([
    'public/vendor/fontawesome-free/css/all.min.css',
    'public/vendor/overlayScrollbars/css/OverlayScrollbars.min.css',
    'public/vendor/adminlte/dist/css/adminlte.min.css',
    'public/vendor/datatables/css/dataTables.bootstrap4.min.css',
    'public/vendor/pace-progress/themes/black/pace-theme-flash.css',
    'public/vendor/select2/css/select2.min.css',
    'public/vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css',
    'public/assets/css/custom.css',
], 'public/assets/css/all.css');
mix.scripts([
    'public/vendor/jquery/jquery.min.js',
    'public/vendor/bootstrap/js/bootstrap.bundle.min.js',
    'public/vendor/overlayScrollbars/js/jquery.overlayScrollbars.min.js',
    'public/vendor/datatables/js/jquery.dataTables.min.js',
    'public/vendor/datatables/js/dataTables.bootstrap4.min.js',
    'public/vendor/pace-progress/pace.min.js',
    'public/vendor/select2/js/select2.full.min.js',
    'public/vendor/adminlte/dist/js/adminlte.min.js',
    'public/assets/js/custom.js',
], 'public/assets/js/all.js');


if (mix.inProduction()) {
    mix.version();
} else {
    // Uses inline source-maps on development
    mix.webpackConfig({
        devtool: 'inline-source-map'
    });
}

mix.browserSync('http://local.admin-studio.com');
