/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');


window.Vue = require('vue');

import VueRouter from 'vue-router'
import Vuex from "vuex";
import * as VueGoogleMaps from 'vue2-google-maps'
import GmapCluster from 'vue2-google-maps/dist/components/cluster' // replace src with dist if you have Babel issues
import Asd from 'vue-picture-input'
import Datepicker from 'vuejs-datepicker';    // only date
import Datetimee from 'vuejs-datetimepicker'; // datetime

Vue.use(VueRouter)
Vue.use(Vuex);

Vue.use(VueGoogleMaps, {
  load: {
    key: 'AIzaSyDJzEhdTE26JFLY0JcKnTPivQy0L_9zaPA',
    libraries: 'places', // This is required if you use the Autocomplete plugin
    // OR: libraries: 'places,drawing'
    // OR: libraries: 'places,drawing,visualization'
    // (as you require)

    //// If you want to set the version, you can do so:
    // v: '3.26',
  },

  //// If you intend to programmatically custom event listener code
  //// (e.g. `this.$refs.gmap.$on('zoom_changed', someFunc)`)
  //// instead of going through Vue templates (e.g. `<GmapMap @zoom_changed="someFunc">`)
  //// you might need to turn this on.
  // autobindAllEvents: false,

  //// If you want to manually install components, e.g.
  // import {GmapMarker} from 'vue2-google-maps/src/components/marker'
  //// then disable the following:
  // installComponents: true,
})
Vue.component('GmapCluster', GmapCluster)
 // Vue.component('GmapMarker', GmapMarker)
// COMPONENTS

Vue.component('example',            require('./components/ExampleComponent.vue').default);
Vue.component('app',                require('./components/app.vue').default);
// Vue.component('stripe',   require('vue-stripe-checkout').default);
// Vue.component('starr',              require('vue-star-rating').default);
// FOR US
Vue.component('navBar',             require('./components/localsavailables/navBar.vue').default);
Vue.component('enrutador',          require('./components/localsavailables/base.vue').default);
Vue.component('elemento',           require('./components/localsavailables/element.vue').default);
Vue.component('list',               require('./components/localsavailables/list.vue').default);
Vue.component('mapa',               require('./components/localsavailables/map.vue').default);
Vue.component('individual',         require('./components/localsavailables/individual.vue').default);
Vue.component('picture-input',      require('vue-picture-input').default);
Vue.component('datee',              Datetimee);



//
const routes = [
 {path: '/available-locals',                    component: Vue.component('list')},
 {path: '/available-locals/map',                component: Vue.component('mapa')},
]

var router = new VueRouter({
  routes: routes,
  mode: 'history'
});

var store = new Vuex.Store({
 state: {
   name:"alex"
 },
 getters: {
   firstName: state => {
     return state.name
   }
 },
 mutations: {},
 actions: {}
});


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 var dt = {
   map:false,
   test:"alex"
 }

const app = new Vue({
    el: '#app',
    router: router,
    data: {
      test:"alex"
    },
    store

});
