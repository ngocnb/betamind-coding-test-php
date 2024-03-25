import ApiService from "./BaseApiService";

class UserApiService {
    login(data) {
        return ApiService.post("/users/login.json", data);
    }

    signUp(data) {
        return ApiService.post("/api/users/register.json", data);
    }
}

export default new UserApiService();
