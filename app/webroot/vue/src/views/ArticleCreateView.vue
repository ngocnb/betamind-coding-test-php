<template>
    <div class="w-max-600 m-0-auto">
        <h1 class="va-h1">Create new Article</h1>
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
            <VaButton @click="submitForm">Create</VaButton>
        </VaForm>
    </div>
</template>
<script>
import ArticleApiService from "@/services/ArticleApiService";
import {useUserStore} from "@/stores/user.js";

const ArticleCreateView = {
    name: "ArticleCreateView",
    data() {
        return {
            form: {
                title: "",
                body: "",
            },
        };
    },
    computed: {
        $userStore: () => useUserStore()
    },
    methods: {
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
                const response = await ArticleApiService.create(this.form, this.$userStore.getUser.token);
                if (response.data.success !== undefined) {
                    let message = response.data.success ? "Article created successfully" : "Error creating article!";
                    this.$vaToast.init({
                        message: message,
                        color: response.data.success ? "success" : "danger",
                    });

                    if (response.data.success) {
                        this.$router.push({ name: "home" });
                    }
                } else {
                    console.log("error", response.data);
                    this.$vaToast.init({
                        message: "Error creating article!",
                        color: "danger",
                    });
                }
            } catch (error) {
                console.log("error", error);
                this.$vaToast.init({
                    message: "Error creating article!",
                    color: "danger",
                });
            }
        },
    },
    mounted() {
        if (!this.$userStore.isLoggedIn) {
            this.$vaToast.init({
                message: "Please log in to create an article",
                color: "danger",
            });
            this.$router.push({ name: "home" });
        }
    },
};

export default ArticleCreateView;
</script>
