import { createWebHistory, createRouter } from "vue-router";

import Home from '../components/Home';

import Register from '../components/users/Register';
import Login from '../components/users/Login';
import Logout from '../components/users/Logout';

import Posts from '../components/posts/Resources';
import Post from '../components/posts/Resource';
import PostCreate from '../components/posts/Create';
import PostEdit from '../components/posts/Edit';
import PostDelete from '../components/posts/Delete';

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
    },
    {
        name: 'post',
        path: '/post/:id',
        component: Post
    },
    {
        name: 'postCreate',
        path: '/post/create',
        component: PostCreate
    },
    {
        name: 'postEdit',
        path: '/post/:id/edit',
        component: PostEdit
    },
    {
        name: 'postDelete',
        path: '/post/:id/delete',
        component: PostDelete
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
    linkExactActiveClass: 'active',
});

export default router;
