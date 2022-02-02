
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./update');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));

const app = new Vue({
    el: '#app'
});

$('.js-check').on('click', function() {
    $('#type-' + $(this).data('id') + ' input[type=\'checkbox\']')
    .attr('checked', true);
});

$('.js-uncheck').on('click', function() {
    $('#type-' + $(this).data('id') + ' input[type=\'checkbox\']')
    .attr('checked', false);
});

$('.js-check-all').on('click', function() {
    $('input[type=\'checkbox\']')
    .not('input[name=\'processed\']')
    .attr('checked', true);
});

$('.js-uncheck-all').on('click', function() {
    $('input[type=\'checkbox\']')
    .not('input[name=\'processed\']')
    .attr('checked', false);
});

$('.js-product-row').on('click', function() {
    $('#check-' + $(this).data('id'))
    .attr('checked',
        !$('#check-' + $(this).data('id')).attr('checked')
    );
});

$('.js-category').on('change', function() {
    document.location.href = "product?category=" + $(this).val();
});
