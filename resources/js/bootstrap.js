window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {
  console.error('ERROR DE BOOSTRAP.JS')

}

import VueRouter from 'vue-router'
/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
window.axios = require('axios');
window.name = "Enterprise";
// PROBLEMA CORS
// window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// window.axios.defaults.headers.common['Access-Control-Allow-Origin'] = '*';
// window.axios.defaults.headers.common['Access-Control-Allow-Methods'] = 'DELETE, POST, GET, OPTIONS';
// window.axios.defaults.headers.common['Access-Control-Allow-Headers'] = 'Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
  broadcaster: 'pusher',
    key:'289463930',
    wsHost: '34.121.39.177',
    wsPort: 6001,
    disableStats: true,
    cluster:'eu',
    forceTLS:false

});

console.log(window.Echo)
