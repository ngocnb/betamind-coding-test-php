<script>
import ArticleApiService from "@/services/ArticleApiService";

const HomeView = {
    name: "HomeView",
    data() {
        return {
            articles: [],
            page: 1,
        };
    },
    methods: {
        async fetchArticles() {
            const response = await ArticleApiService.getAll(this.page);
            console.log("response", response);
            this.articles = response.data.articles;
            console.log("articles", this.articles);
        },
        async appendRecordsAsync() {
            this.page++;
            const response = await ArticleApiService.getAll(this.page);
            this.articles = [...this.articles, ...response.data.articles];
        },
    },
    mounted() {
        this.fetchArticles();
    },
};

export default HomeView;
</script>

<template>
    <h1 class="va-h1">New Articles</h1>
    <div class="flex flex-wrap gap-5 articles-wrapper">
        <VaCard
            square
            outlined
            v-for="(article, index) in articles"
            :key="index"
            class="article-card"
        >
            <VaCardTitle>{{ article.title }}</VaCardTitle>
            <VaCardContent>
                {{ article.body.substring(0, 100) }}...
            </VaCardContent>
        </VaCard>
    </div>
    <div class="button-wrapper">
        <VaButton @click="appendRecordsAsync()">Load More</VaButton>
    </div>
</template>

<style>
.articles-wrapper {
    justify-content: space-between;
    display: flex;
}
.article-card {
    width: 24%;
}
.infinite-scroll-wrapper {
    height: 80vh;
}
.button-wrapper {
    margin-top: 20px;
    display: flex;
    justify-content: center;
}
@media screen and (max-width: 768px) {
    .article-card {
        width: 48%;
    }
}
</style>
