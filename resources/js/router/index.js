import Vue from 'vue'
import router from 'vue-router'
import Home from '../components/Home'
import Register from '../components/Register'
import Login from '../components/Login'
import ForgetPassword from '../components/ForgetPassword'
import Dashboard from '../components/Dashboard'
import BuyPower from '../components/Services/BuyPower'
import BuyAirtime from '../components/Services/BuyAirtime'
import BuyData from '../components/Services/BuyData'
import SubscribeTv from '../components/Services/SubscribeTv'
import About from '../components/About'
import Member from '../components/Member'
import Faq from '../components/Faq'

Vue.use(router)

const routes = [
    {path: '/', component: Home},
    {path: '/register', component: Register},
    {path: '/login', component: Login},
    {path: '/forget_password', component: ForgetPassword},
    {path: '/dashboard', component: Dashboard},
    {path: '/buy_power', component: BuyPower},
    {path: '/buy_airtime', component: BuyAirtime},
    {path: '/buy_data', component: BuyData},
    {path: '/subscribe_tv', component: SubscribeTv},
    {path: '/about', component: About},
    {path: '/member', component: Member},
    {path: '/faq', component: Faq},
]

export default new router({routes, mode: 'history'})