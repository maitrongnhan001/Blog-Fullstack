import Vue from 'vue';
import Router from 'vue-router';

import home from './components/Pages/home.vue';
import tags from './admin/Pages/tags.vue';
import category from './admin/pages/category.vue';
import adminUsers from './admin/pages/adminusers.vue';

Vue.use(Router); 

const routes = [
    {
        path: '/',
        component: home
    },
    {
        path: '/tags',
        component: tags
    },
    {
        path: '/category',
        component: category
    },
    {
        path: '/adminusers',
        component: adminUsers
    },
];

export default new Router({
    mode: 'history',
    routes
});