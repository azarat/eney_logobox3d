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

// Make Laravel Mix ignore .svgs
Mix.listen('configReady', function (config) {
  const rules = config.module.rules;
  const targetRegex = /(\.(png|jpe?g|gif)$|^((?!font).)*\.svg$)/;

  for (let rule of rules) {
    if (rule.test.toString() == targetRegex.toString()) {
      rule.exclude = /\.svg$/;
      break;
    }
  }
});

// Hande .svgs with html-loader instead
mix.webpackConfig({
  module: {
    rules: [{
      test: /\.svg$/,
      use: [{
        loader: 'html-loader',
        options: {
          minimize: true
        }
      }]
    }]
  }
});

mix.js('resources/assets/js/app.js', 'public/js')
  .sass('resources/assets/sass/app.scss', 'public/css')
  .version();

mix.js('resources/assets/js/iframe.js', 'public/js')
  .version();
