import Vue from 'vue';
import Router from 'vue-router';

import home from './components/Pages/home.vue';
import tags from './admin/Pages/tags.vue';
import category from './admin/pages/category.vue';
import adminUsers from './admin/pages/adminusers.vue';
import role from './admin/pages/role.vue';
import login from './admin/pages/login.vue';

Vue.use(Router); 

const routes = [
    {
        path: '/',
        component: home
    },
    {
        path: '/login',
        component: login
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
    {
        path: '/role',
        component: role
    }
];

export default new Router({
    mode: 'history',
    routes
});