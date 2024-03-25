<template>
    <div class="w-max-600 m-0-auto">
        <h1 class="va-h1">Log in</h1>
        <VaForm ref="formRef" class="flex flex-col items-baseline gap-6">
            <VaInput
                v-model="form.email"
                :rules="[
                    (value) =>
                        (value && value.length > 0) || 'Email is required',
                    (value) => /.+@.+\..+/.test(value) || 'Email must be valid',
                ]"
                label="email"
            />
            <VaInput
                v-model="form.password"
                :rules="[
                    (value) =>
                        (value && value.length > 0) || 'Password is required',
                    (value) =>
                        value.length >= 8 ||
                        'Password must be at least 8 characters',
                ]"
                label="password"
                type="password"
            />
            <VaButton @click="submitForm">Log in</VaButton>
        </VaForm>
    </div>
</template>
<script>
import UserApiService from "@/services/UserApiService";
import { useUserStore } from "@/stores/user";

const LoginView = {
    name: "LoginView",
    data() {
        return {
            form: {
                email: "",
                password: "",
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
                    title: "Error",
                    message: "Please fix the errors in the form",
                    variant: "error",
                });
                return;
            }
            try {
                const response = await UserApiService.login(this.form);
                if (response.data.success !== undefined) {
                    let message = response.data.success
                        ? "Log in successfully!"
                        : "Log in failed! Please check again!";
                    this.$vaToast.init({
                        message: message,
                        color: response.data.success ? "success" : "danger",
                    });
                    if (response.data.success) {
                        this.$userStore.setUser(response.data.user);
                        // save user info to local storage
                        localStorage.setItem(
                            "user",
                            JSON.stringify(response.data.user)
                        );
                    }
                    this.$router.push({ name: "home" });
                } else {
                    this.$vaToast.init({
                        message: "Log in failed! Please check again!",
                        color: "danger",
                    });
                }
            } catch (error) {
                console.log("error", error);
                this.$vaToast.init({
                    message: "Log in failed! Please check again!",
                    color: "danger",
                });
            }
        },
    },
};

export default LoginView;
</script>
