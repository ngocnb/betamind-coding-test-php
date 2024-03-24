<script setup>
import { RouterLink, RouterView } from "vue-router";
</script>

<template>
    <VaLayout style="height: 100%; width: 100%">
        <template #top>
            <VaNavbar color="primary" class="mb-3">
                <template #left>
                    <VaNavbarItem class="logo"> Home </VaNavbarItem>
                </template>
                <template #right>
                    <VaNavbarItem> Sign Up </VaNavbarItem>
                    <VaNavbarItem> Log in </VaNavbarItem>
                </template>
            </VaNavbar>
        </template>

        <template #content>
            <RouterView />
        </template>
    </VaLayout>
</template>

<style scoped></style>
