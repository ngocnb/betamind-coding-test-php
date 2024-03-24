import ApiService from "./BaseApiService";

class ArticleApiSerivce {
    getAll() {
        return ApiService.get("/api/articles.json");
    }

    get(id) {
        return ApiService.get(`/api/articles/${id}.json`);
    }

    create(data) {
        return ApiService.post("/api/articles.json", data);
    }

    update(id, data) {
        return ApiService.put(`/api/articles/${id}.json`, data);
    }

    delete(id) {
        return ApiService.delete(`/api/articles/${id}.json`);
    }
}

export default new ArticleApiSerivce();
