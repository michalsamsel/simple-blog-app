import { createWebHistory, createRouter } from "vue-router";
//Index page of app
import Home from '../components/Home';
//Views for user authentication
import Register from '../components/users/Register';
import Login from '../components/users/Login';
import Logout from '../components/users/Logout';
//Views for posts CRUD
import Posts from '../components/posts/Resources';
import Post from '../components/posts/Resource';
import PostCreate from '../components/posts/Create';
import PostEdit from '../components/posts/Edit';
import PostDelete from '../components/posts/Delete';
//View for displaying user posts
import UserPosts from '../components/postsAndUsers/Resources'
//View for comment Update & Delete 
import CommentEdit from '../components/comments/Edit';
import CommentDelete from '../components/comments/Delete';

export const routes = [
    //Index page of app
    {
        name: 'home',
        path: '/',
        component: Home
    },
    //Views for user authentication
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
    //Views for posts CRUD
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
    //View for displaying user posts
    {
        name: 'userPosts',
        path: '/user/:id/posts',
        component: UserPosts
    },
    //View for comment Update & Delete 
    {
        name: 'commentEdit',
        path: '/comment/:id/edit',
        component: CommentEdit
    },
    {
        name: 'commentDelete',
        path: '/comment/:id/delete',
        component: CommentDelete
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
    linkExactActiveClass: 'active', //For displaying on navbar which route is active
});

export default router;
