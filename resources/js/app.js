require('./bootstrap');
window.Vue = require('vue');
import router from './router'
import VueCookies from 'vue-cookies'
import appdata from './mixins/appdata'

Vue.use(router)
Vue.use(VueCookies)
Vue.mixin(appdata)
// console.log(this.user)
// set default config for cookies
VueCookies.config('30d')

const files = require.context('./', true, /\.vue$/i);
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));


const app = new Vue({
    router,
    el: '#app'
});
