var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass('app.scss');
    mix.sass('tristate-checkbox.scss');
    mix.styles([
        'resources/assets/css/sb-admin-2.css',
        'resources/assets/css/switch.css',
    ], 'public/css/styles.css', './');
});
