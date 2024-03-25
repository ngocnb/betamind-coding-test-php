<script>
import { RouterLink, RouterView } from "vue-router";
import { useUserStore } from "@/stores/user";

const App = {
    computed: {
        $userStore: () => useUserStore()
    },
    data() {
        return {
            user: null,
        };
    },
    methods: {
        logout() {
            this.$userStore.logout();
            this.$vaToast.init({
                message: "Log out successfully!",
                color: "success",
            });
            this.$router.push({ name: "home" });
        },
    },
    mounted() {
        // get user from store
        this.user = this.$userStore.getUser;

        // if user is null, get user info from local storage
        const user = localStorage.getItem("user");
        if (user) {
            this.user = JSON.parse(user);
            this.$userStore.setUser(this.user);
        }
    }
}
export default App;
</script>

<template>
    <VaLayout style="height: 100%; width: 100%">
        <template #top>
            <VaNavbar color="primary" class="mb-3">
                <template #left>
                    <VaNavbarItem class="logo">
                        <RouterLink to="/"> Home </RouterLink>
                    </VaNavbarItem>
                </template>
                <template #right v-if="$userStore.isLoggedIn === false">
                    <VaNavbarItem>
                        <RouterLink to="/signup"> Sign Up </RouterLink>
                    </VaNavbarItem>
                    <VaNavbarItem>
                        <RouterLink to="/login"> Log in </RouterLink>
                    </VaNavbarItem>
                </template>
                <template #right v-if="$userStore.isLoggedIn === true">
                    <VaNavbarItem>
                        {{ $userStore.getUser.email }}
                    </VaNavbarItem>
                    <VaNavbarItem>
                        <RouterLink to="/article/create"> Create new Article </RouterLink>
                    </VaNavbarItem>
                    <VaNavbarItem @click="logout()" style="cursor: pointer;">
                        Log out
                    </VaNavbarItem>
                </template>
            </VaNavbar>
        </template>

        <template #content>
            <RouterView />
        </template>
    </VaLayout>
</template>

<style scoped>
.va-navbar__item a {
    color: white;
}
</style>
