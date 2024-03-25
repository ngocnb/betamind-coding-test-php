import { defineStore } from "pinia";

export const useUserStore = defineStore({
    id: "user",
    state: () => ({
        user: null,
    }),
    actions: {
        setUser(user) {
            this.user = user;
        },
        logout() {
            this.user = null;
            // remove user info in local storage
            localStorage.removeItem("user");
        },
    },
    getters: {
        isLoggedIn() {
            return !!this.user;
        },
        getUser() {
            return this.user;
        }
    },
});
