
import UserComponent from "./components/UserComponent";
import About from './components/About';
import Register from './auth/Register';
import Login from './auth/Login';
import Dashboard from './components/Dashboard';
import CategoryComponent from "./components/CategoryComponent";
import StockComponent from "./components/StockComponent";
import ImportComponent from "./components/ImportComponent";
import RateComponent from "./components/RateComponent";
import InvoiceComponent from "./components/InvoiceComponent";

export default {
    mode: 'history',
    fallback: true,
    routes:[
        {
            name: 'about',
            path: '/about',
            component: About,
            meta: {requiresAuth: true}
        },
        {
            name: 'register',
            path: '/register',
            component: Register,
            meta: {requiresAuth: true}
        },
        {
            name: 'login',
            path: '/login',
            component: Login,
            meta: {guest: true}
        },
        {
            name: 'dashboard',
            path: '/',
            component: Dashboard,
            meta: {requiresAuth: true}
        },
        {
            name: 'user',
            path: '/users',
            component: UserComponent,
            meta: {requiresAuth: true}
        },
        {
            name: 'category',
            path: '/categories',
            component: CategoryComponent,
            meta: {requiresAuth: true}
        },
        {
            name: 'stock',
            path: '/stocks',
            component: StockComponent,
            meta: {requiresAuth: true}
        },
        {
            name: 'import',
            path: '/imports',
            component: ImportComponent,
            meta: {requiresAuth: true}
        },
        {
            name: 'rate',
            path: '/rates',
            component: RateComponent,
            meta: {requiresAuth: true}
        },
        {
            name: 'InvoiceComponent',
            path: '/invoice',
            component: InvoiceComponent,
            meta: {requiresAuth: true}
        }

    ]
}

