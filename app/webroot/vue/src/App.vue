<script setup>
import { RouterLink, RouterView } from "vue-router";
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
                <template #right>
                    <VaNavbarItem>
                        <RouterLink to="/signup"> Sign Up </RouterLink>
                    </VaNavbarItem>
                    <VaNavbarItem>
                        <RouterLink to="/login"> Log in </RouterLink>
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
