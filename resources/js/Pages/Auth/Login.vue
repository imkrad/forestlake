<template>
    <Head title="Log in"/>
    <div class="auth-page-wrapper pt-5 d-flex justify-content-center align-items-center min-vh-100">
        <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
            <!-- <div class="bg-overlay"></div> -->

         
        </div>
        <div class="auth-page-content">
            <BContainer>

                <BRow class="justify-content-center">
                    <BCol md="8" lg="6" xl="5">
                        <BCard no-body class="mt-4">

                            <BCardBody class="p-4">
                                <div class="row mb-2 text-center">
                                    <div class="col-12 mb-2">
                                        <img src="@assets/images/logo-sm-dark.png" alt="" style="height: auto; width: 250px;">
                                    </div>
                                </div>
                                <div v-if="status" class="alert alert-success text-success">
                                    {{ status }}
                                </div>
                                <div class="p-2 mb-4 mt-5">
                                    <form class="customform" @submit.prevent="submit">

                                        <div class="mb-2">
                                            <InputLabel for="email" value="Email Address" />
                                            <TextInput id="email" v-model="form.email" type="email" class="form-control" placeholder="Please enter email address"/>
                                            <InputError :message="form.errors.email" />
                                        </div>

                                        <div class="mb-3">
                                            <InputLabel for="password" value="Password" />
                                            <div class="position-relative auth-pass-inputgroup mb-3">
                                                <input :type="togglePassword ? 'text' : 'password'" class="form-control pe-5" placeholder="Please enter password" id="password-input" v-model="form.password" autocomplete="password">
                                                <BButton variant="link" class="position-absolute end-0 top-0 text-decoration-none text-muted" type="button" id="password-addon" @click="togglePassword = !togglePassword">
                                                    <i class="ri-eye-fill align-middle"></i>
                                                </BButton>
                                                <InputError :message="form.errors.password" />
                                            </div>
                                        </div>

                                        <!-- <div class="form-check">
                                            <Checkbox v-model:checked="form.remember" name="remember" class="form-check-input" id="auth-remember-check" />
                                            <label class="form-check-label" for="auth-remember-check">Remember
                                                me</label>
                                        </div> -->

                                        <div class="mt-4">
                                            <BButton variant="dark" class="w-100" type="submit" :disabled="form.processing">Log In</BButton>
                                        </div>

                                        <!-- <div class="mt-4 fs-10 text-center">
                                            <p class="mb-0">Forgot your password? 
                                                <Link href="/forgot-password" class="fw-semibold text-primary text-decoration-underline"> Click here</Link>
                                            </p>
                                        </div> -->

                                    </form>
                                </div>
                            </BCardBody>
                        </BCard>
                    </BCol>
                </BRow>
            </BContainer>
        </div>
    </div>
</template>
<script setup>
import { useForm } from '@inertiajs/vue3';
import Checkbox from '@/Shared/Components/Forms/Checkbox.vue';
import InputError from '@/Shared/Components/Forms/InputError.vue';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
defineProps({
    canResetPassword: Boolean,
    status: String
});
const form = useForm({
    email: '',
    password: '',
    remember: false,
});
const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post('/login', {
        onFinish: () => form.reset('password'),
    });
};
</script>
<script>
export default {
    layout: null,
    data() {
        return {
            togglePassword: false
        }
    },
}
</script>
<style scoped>
input::placeholder {
    font-size: 10px;
}
</style>