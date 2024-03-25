import { createRouter, createWebHistory } from "vue-router";
import HomeView from "../views/HomeView.vue";
import ArticleView from "../views/ArticleView.vue";
import LoginView from "../views/LoginView.vue";
import SignUpView from "../views/SignUpView.vue";
import ArticleCreateView from "../views/ArticleCreateView.vue";
import ArticleEditView from "../views/ArticleEditView.vue";

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: "/",
            name: "home",
            component: HomeView,
        },
        {
            path: "/article/:id",
            name: "article-detail",
            component: ArticleView,
        },
        {
            path: "/article/create",
            name: "article-create",
            component: ArticleCreateView,
        },

        {
            path: "/article/edit/:id",
            name: "article-edit",
            component: ArticleEditView,
        },
        {
            path: "/login",
            name: "login",
            component: LoginView,
        },
        {
            path: "/signup",
            name: "signup",
            component: SignUpView,
        },
    ],
});

export default router;
