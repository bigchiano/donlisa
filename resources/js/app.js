require('./bootstrap');
window.Vue = require('vue');
window.CryptoJS = require("crypto-js");
import router from './router'
import VueCookies from 'vue-cookies'
import appdata from './mixins/appdata'


Vue.use(router)
Vue.use(VueCookies)
Vue.mixin(appdata)

// set default config for cookies
VueCookies.config('30d')

const files = require.context('./', true, /\.vue$/i);
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));


let token = $cookies.get('token');
if($cookies.isKey('token')) {
    // // seet default headers for axios
    axios.defaults.baseURL = 'http://donlisa.com';
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

const app = new Vue({
    router,
    el: '#app'
});
