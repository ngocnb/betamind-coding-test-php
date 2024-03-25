<template>
    <div class="w-max-600 m-0-auto">
        <h1 class="va-h1">Edit article</h1>
        <VaForm ref="formRef" class="flex flex-col items-baseline gap-6">
            <VaInput
                v-model="form.title"
                :rules="[
                    (value) =>
                        (value && value.length > 0) || 'Title is required',
                    (value) =>(value && value.length < 255) || 'Title must be less than 255 characters',
                ]"
                label="title"
            />
            <VaTextarea
                v-model="form.body"
                :rules="[
                    (value) =>
                        (value && value.length > 0) || 'Content is required',
                    (value) => (value && value.length < 4000) || 'Title must be less than 4000 characters',
                ]"
                label="content"
                rows="10"
                style="height: 250px;"
            />
            <VaButton @click="submitForm">Update</VaButton>
        </VaForm>
    </div>
</template>
<script>
import ArticleApiService from "@/services/ArticleApiService";
import {useUserStore} from "@/stores/user.js";

const ArticleEditView = {
    name: "ArticleEditView",
    data() {
        return {
            form: {
                title: "",
                body: "",
            },
            article: null,
            isAuthor: false,
        };
    },
    computed: {
        $userStore: () => useUserStore()
    },
    methods: {
        async fetchArticle() {
            const id = this.$route.params.id;
            const token = this.$userStore.getUser ? this.$userStore.getUser.token : null;
            const response = await ArticleApiService.get(id, token);
            this.article = response.data.article;
            this.isAuthor = response.data.is_author;

            this.form.title = this.article.title;
            this.form.body = this.article.body;

            // check if user is the author of the article
            if (!this.isAuthor) {
                this.$vaToast.init({
                    message: "You are not the author of this article",
                    color: "danger",
                });
                this.$router.push({ name: "home" });
            }
        },
        async submitForm() {
            const isValid = await this.$refs.formRef.validate();
            if (!isValid) {
                this.$vaToast.init({
                    message: "Please fix the errors in the form",
                    color: "danger",
                });
                return;
            }
            try {
                const response = await ArticleApiService.update(this.$route.params.id, this.form, this.$userStore.getUser.token);
                if (response.data.success !== undefined) {
                    let message = response.data.success ? "Article updated successfully" : "Error updating article!";
                    this.$vaToast.init({
                        message: message,
                        color: response.data.success ? "success" : "danger",
                    });

                    if (response.data.success) {
                        this.$router.push({ name: "article-detail", params: { id: this.$route.params.id } });
                    }
                }
            } catch (error) {
                this.$vaToast.init({
                    message: "Error updating article!",
                    color: "danger",
                });
            }
        }
    },
    mounted() {
        // check user logged in or not
        if (!this.$userStore.isLoggedIn) {
            this.$vaToast.init({
                message: "Please log in to edit an article",
                color: "danger",
            });
            this.$router.push({name: "login"});
        }

        // fetch article data
        this.fetchArticle();
    }
};

export default ArticleEditView;
</script>
