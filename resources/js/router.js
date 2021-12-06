import Vue from 'vue';
import Router from 'vue-router';
import firstpage from './components/Pages/myFirstViewPage.vue';

Vue.use(Router);

const routes = [
    {
        path: '/my-new-vue-route',
        component: firstpage
    }
];

export default new Router({
    mode: 'history',
    routes
});