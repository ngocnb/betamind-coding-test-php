<template>
    <div class="w-max-600 m-0-auto">
        <h1 class="va-h1">Sign up</h1>
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
            <VaInput
                v-model="form.confirmPassword"
                :rules="[
                    (value) =>
                        (value && value.length > 0) ||
                        'Confirm password is required',
                    (value) =>
                        value === form.password || 'Passwords must match',
                ]"
                label="confirm password"
                type="password"
            />
            <VaButton @click="submitForm">Sign up</VaButton>
        </VaForm>
    </div>
</template>
<script>
import UserApiService from "@/services/UserApiService";

const SignUpView = {
    name: "SignUpView",
    data() {
        return {
            form: {
                email: "",
                password: "",
                confirmPassword: "",
            },
        };
    },
    methods: {
        async submitForm() {
            const isValid = await this.$refs.formRef.validate();
            if (!isValid) {
                this.$vaToast.init({
                    message: "Please fix all fields's error!",
                    color: "danger",
                });
                return;
            }
            try {
                const response = await UserApiService.signUp(this.form);
                console.log("response", response);
                this.$router.push({ name: "login" });
            } catch (error) {
                console.error("error", error);
            }
        },
    },
};

export default SignUpView;
</script>
