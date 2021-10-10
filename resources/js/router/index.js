import { createWebHistory, createRouter } from "vue-router";

import Home from '../components/Home';
import Register from '../components/users/Register';
import Login from '../components/users/Login';
import Logout from '../components/users/Logout';
import Posts from '../components/posts/Resources';

export const routes = [
    {
        name: 'home',
        path: '/',
        component: Home
    },
    {
        name: 'register',
        path: '/register',
        component: Register
    },
    {
        name: 'login',
        path: '/login',
        component: Login
    },
    {
        name: 'logout',
        path: '/logout',
        component: Logout
    },
    {
        name: 'posts',
        path: '/posts',
        component: Posts
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
    linkExactActiveClass: 'active',
});

export default router;
