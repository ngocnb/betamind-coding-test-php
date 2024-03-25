<template>
    <div class="w-max-600 m-0-auto" v-if="article !== null">
        <h1 class="va-h1">{{ article.title }}</h1>
        <p class="va-body-1" style="white-space: pre-line" v-html="article.body"></p>
        <p class="va-body-1" style="margin-top: 20px; font-weight: bold">By: {{ article.user.email }}&nbsp&nbsp&nbsp&nbsp Like Count: {{ article.like_count }}</p>
        <div class="" style="margin-top: 20px">
            <VaButton v-if="isAuthor" @click="deleteArticle()" class="mr-6 mb-2">Delete</VaButton>
            <VaButton v-if="isAuthor" @click="editArticle()" class="mr-6 mb-2">Edit</VaButton>
            <VaButton v-if="reacted" color="success" icon="thumb_up" class="mr-6 mb-2">Liked</VaButton>
            <VaButton v-else @click="reactArticle()" icon="thumb_up" class="mr-6 mb-2">Like</VaButton>
        </div>
    </div>
    <div class="w-max-600 m-0-auto" v-else>
        <p class="va-body-1">Loading...</p>
    </div>
</template>
<script>
import ArticleApiService from "@/services/ArticleApiService";
import { useUserStore } from "@/stores/user.js";

const ArticleView = {
    name: "ArticleView",
    data() {
        return {
            article: null,
            reacted: false,
            isAuthor: false
        };
    },
    computed: {
        $userStore: () => useUserStore(),
    },
    methods: {
        async fetchArticle() {
            const id = this.$route.params.id;
            const token = this.$userStore.getUser ? this.$userStore.getUser.token : null;
            const response = await ArticleApiService.get(id, token);
            this.article = response.data.article;
            this.isAuthor = response.data.is_author;
            this.reacted = response.data.reacted;
        },
        async reactArticle() {
            const id = this.$route.params.id;
            // check if user is logged in
            if (!this.$userStore.isLoggedIn) {
                this.$vaToast.init({
                    message: "Please log in to like this article",
                    color: "danger",
                });
                return;
            }

            const response = await ArticleApiService.react(id, this.$userStore.getUser.token);
            if (response.data.success !== undefined) {
                this.$vaToast.init({
                    message: response.data.success ? "Article liked!" : "Error liking article!",
                    color: response.data.success ? "success" : "danger",
                });
                this.reacted = response.data.success;
                if (response.data.success) {
                    await this.fetchArticle();
                }
            }
        },
        async deleteArticle() {
            const id = this.$route.params.id;

            // show confirmation dialog
            const confirm = window.confirm("Are you sure you want to delete this article?");
            if (!confirm) {
                return;
            }

            const response = await ArticleApiService.delete(id, this.$userStore.getUser.token);
            if (response.data.success !== undefined) {
                this.$vaToast.init({
                    message: response.data.success ? "Article deleted!" : "Error deleting article!",
                    color: response.data.success ? "success" : "danger",
                });
                if (response.data.success) {
                    this.$router.push({ name: "home" });
                }
            }
        },
        editArticle() {
            const id = this.$route.params.id;
            this.$router.push({ name: "article-edit", params: { id } });
        },
    },
    mounted() {
        this.fetchArticle();
    },
};

export default ArticleView;
</script>
