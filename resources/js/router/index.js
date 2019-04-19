import Vue from 'vue'
import router from 'vue-router'
import Home from '../components/Home'
import Register from '../components/Register'
import Login from '../components/Login'
import Dashboard from '../components/Dashboard'

Vue.use(router)

const routes = [
    {path: '/', component: Home},
    {path: '/register', component: Register},
    {path: '/login', component: Login},
    {path: '/dashboard', component: Dashboard},
]

export default new router({routes, mode: 'history'})