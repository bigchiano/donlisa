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



// // set default headers for axios
axios.defaults.baseURL = 'http://donlisa.com';
let token = $cookies.get('token');
if($cookies.isKey('token')) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

const app = new Vue({
    router,
    el: '#app'
});

router.beforeResolve((to, from, next) => {
    // If this isn't an initial page load.
    if (to.name) {
        console.log('got here')
    }
    $('.transaction_loader').removeClass('hide')
    next()
})

router.afterEach((to, from) => {
    setTimeout(() => {
        $('.transaction_loader').addClass('hide')
    }, 1500)
})
