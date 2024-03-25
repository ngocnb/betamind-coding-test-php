import ApiService from "./BaseApiService";

class ArticleApiSerivce {
    getAll(page = 1) {
        return ApiService.get(`/api/articles.json?page=${page}`);
    }

    get(id, token = null) {
        ApiService.defaults.headers.common["Token"] = token;
        return ApiService.get(`/api/articles/${id}.json`);
    }

    create(data, token) {
        ApiService.defaults.headers.common["Token"] = token;
        return ApiService.post("/api/articles.json", data);
    }

    update(id, data, token) {
        ApiService.defaults.headers.common["Token"] = token;
        return ApiService.put(`/api/articles/${id}.json`, data);
    }

    delete(id, token) {
        ApiService.defaults.headers.common["Token"] = token;
        return ApiService.delete(`/api/articles/${id}.json`);
    }

    react(id, token) {
        ApiService.defaults.headers.common["Token"] = token;
        return ApiService.post(`/api/articles/${id}/react.json`);
    }
}

export default new ArticleApiSerivce();
