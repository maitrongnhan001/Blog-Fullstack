require('./bootstrap');

import Vue from 'vue';
import router from './router.js';
import ViewUI from "view-design";
import store from './store.js';
import 'view-design/dist/styles/iview.css';
import common from './common.js';
import Editor from 'vue-editor-js/src/index' 
Vue.use(Editor);
Vue.use(ViewUI);
Vue.mixin(common);


Vue.component('mainapp', require('./components/mainapp.vue').default);
const app = new Vue ({
    el: '#app',
    router,
    store 
});