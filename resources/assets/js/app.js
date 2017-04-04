
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.component('sts-user-tickets', require('./components/tickets/User.vue'));
Vue.component('sts-staff-tickets', require('./components/tickets/Staff.vue'));

Vue.component('sts-ticket-details', require('./components/tickets/show/Details.vue'));
Vue.component('sts-ticket-comments', require('./components/tickets/show/Comments.vue'));

Vue.component('sts-registered-users', require('./components/settings/RegisteredUsers.vue'));
Vue.component('sts-settings-nav', require('./components/settings/Nav.vue'));
Vue.component('sts-departments', require('./components/settings/Departments.vue'));
Vue.component('sts-domains', require('./components/settings/Domains.vue'));
Vue.component('sts-staff', require('./components/settings/Staff.vue'));

const app = new Vue({
    el: '#app'
});
