import Vue from 'vue';
import Router from 'vue-router';

import home from './components/Pages/home.vue';
import tags from './admin/Pages/tags.vue';
import category from './admin/pages/category.vue';
import adminUsers from './admin/pages/adminusers.vue';
import role from './admin/pages/role.vue';
import login from './admin/pages/login.vue';
import assignRole from './admin/pages/assignRole';

Vue.use(Router); 

const routes = [
    {
        path: '/',
        component: home,
        name: 'home'
    },
    {
        path: '/login',
        component: login,
        name: 'login'
    },
    {
        path: '/tags',
        component: tags,
        name: 'tags'
    },
    {
        path: '/category',
        component: category,
        name: 'category'
    },
    {
        path: '/adminusers',
        component: adminUsers,
        name: 'adminuser'
    },
    {
        path: '/role',
        component: role,
        name: 'role'
    },
    {
        path: '/assignRole',
        component: assignRole,
        name: 'assignrole'
    }
];

export default new Router({
    mode: 'history',
    routes
});