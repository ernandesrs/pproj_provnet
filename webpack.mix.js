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

mix
    // .js('resources/global/js/app.js', 'public/js')
    // .sass('resources/global/sass/app.scss', 'public/css')
    // .sourceMaps()


    /* global: js */
    .scripts(['node_modules/jquery/dist/jquery.min.js'], 'public/js/jquery.min.js')
    .scripts(['node_modules/jquery-ui-dist/jquery-ui.min.js'], 'public/js/jquery-ui.min.js')
    .scripts(['node_modules/bootstrap/dist/js/bootstrap.bundle.min.js'], 'public/js/bootstrap.bundle.min.js')
    .scripts(['node_modules/@splidejs/splide/dist/js/splide.js'], 'public/js/splide.js')

    .scripts(['resources/js/global/plugins/messages.js'], 'public/js/plugins/messages.js')
    .scripts(['resources/js/global/plugins/buttons.js'], 'public/js/plugins/buttons.js')
    .scripts(['resources/js/global/plugins/backdrops.js'], 'public/js/plugins/backdrops.js')
    .scripts(['resources/js/global/plugins/form-errors.js'], 'public/js/plugins/form-errors.js')
    .scripts(['resources/js/global/scripts.js'], 'public/js/scripts.js')

    /* global: css */
    .css('node_modules/bootstrap-icons/font/bootstrap-icons.css', 'public/css')
    .css('node_modules/boxicons/css/boxicons.css', 'public/css')
    .css('node_modules/@splidejs/splide/dist/css/splide.min.css', 'public/css')


    /* admin: js */
    .scripts(['resources/js/admin/scripts.js'], 'public/js/admin/scripts.js')
    /* admin: css */
    .sass('resources/scss/admin/custom.scss', 'public/css/admin/custom.css')


    /* site: js */
    .scripts(['resources/js/site/scripts.js'], 'public/js/site/scripts.js')
    /* site: css */
    .sass('resources/scss/site/custom.scss', 'public/css/site/custom.css')


    /* auth: js */
    .scripts(['resources/js/auth/scripts.js'], 'public/js/auth/scripts.js')
    /* auth: css */
    .sass('resources/scss/auth/custom.scss', 'public/css/auth/custom.css')
    .sass('resources/scss/admin/previews.scss', 'public/css/admin/previews.css')
    ;
