import Vue from 'vue';
import Router from 'vue-router';
import firstpage from './components/Pages/myFirstViewPage.vue';
import newRoutePage from './components/Pages/newRoutePage.vue';
import hooks from './components/Pages/basic/hooks.vue';
import methods from './components/Pages/basic/methods.vue';

Vue.use(Router);

const routes = [
    {
        path: '/my-new-vue-route',
        component: firstpage
    },
    {
        path: '/new-route',
        component: newRoutePage
    },

    //vue hooks
    {
        path: '/hooks',
        component: hooks
    },
    //vue method
    {
        path: '/method',
        component: methods
    }
];

export default new Router({
    mode: 'history',
    routes
});