/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import App from './App.vue'
import './bootstrap';
import { createApp } from 'vue';
import $ from 'jquery'

import 'bootstrap/dist/js/bootstrap.bundle.min.js'
import 'admin-lte/dist/js/adminlte.min.js'
import 'admin-lte/dist/css/adminlte.min.css'
import "datatables.net-bs4";
import "datatables.net-bs4/css/dataTables.bootstrap4.min.css";
import routes from './routes'
import { createRouter, createWebHistory } from 'vue-router';
import axios from 'axios'
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.css";

window.$ = window.jQuery = $

window.axios = axios

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

// ambil CSRF token dari meta
const token = document
  .querySelector('meta[name="csrf-token"]')
  ?.getAttribute('content')

if (token) {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = token
}
const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const user = window.authUser

    if (to.meta.requiresAuth && !user) {
        window.location.href = '/login'
        return
    }

    if (to.meta.role && user?.role !== to.meta.role) {
        next('/403')
        return
    }

    next()
})

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp(App);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

app.use(router)
app.use(flatpickr)
app.mount('#app');
