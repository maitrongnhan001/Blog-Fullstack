require('./bootstrap');

import Vue from 'vue';
import router from './router.js';
import ViewUI from "view-design";
import 'view-design/dist/styles/iview.css';
//import '/css/all.css';
Vue.use(ViewUI);


Vue.component('mainapp', require('./components/mainapp.vue').default);
const app = new Vue ({
    el: '#app',
    router 
});