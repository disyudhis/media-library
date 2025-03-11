<script setup>
import {
    Head,
    useForm,
} from '@inertiajs/vue3';
import {
    ref,
} from 'vue';
import {
    Form
} from '@primevue/forms';
import {
    Divider,
    InputText,
    Message,
    Password,
    Button,
    useToast,
    Toast
} from 'primevue';
import {
    zodResolver
} from '@primevue/forms/resolvers/zod';
import {
    z
} from 'zod';

defineProps({errors: Object})

const toast = useToast();

const form = useForm({
    email: null,
    password: null
})

const initialValues = ref({
    email: '',
    password: ''
});

const resolver = zodResolver(
    z.object({
        email: z.string().email(),
        password: z.string()
            .min(3, {
                message: 'Minimum 3 characters.'
            })
            .max(8, {
                message: 'Maximum 8 characters.'
            })

    })
);

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
        onError: (errors) => {
            if (Object.keys(errors).length > 0) {
                Object.entries(errors).forEach(([field, message]) => {
                    toast.add({
                        severity: 'error',
                        summary: `Error pada ${field}`,
                        detail: message,
                        life: 3000
                    });
                });
            } else {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: 'Terjadi kesalahan saat login',
                    life: 3000
                });
            }
        }
    });
};
</script>

<template>

    <Head title="Log in" />
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <Toast />
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 overflow-hidden sm:rounded-lg text-black">
            <!-- Logo -->
            <div>
                <h1 class="text-4xl font-medium text-center">Log in</h1>
            </div>
            <Divider align="center">
                <small>Log in here</small>
            </Divider>
            <!-- Form -->
            <div>
                <Form v-slot="$form" :initialValues :resolver @submit="submit" class="flex flex-col gap-4">
                    <div class="flex flex-col gap-4">
                        <div class="flex flex-col">
                            <label for="email">Email</label>
                            <InputText id="email" v-model="form.email" name="email" fluid type="email" aria-describedby="email-help" />
                            <Message v-if="$form.email?.invalid" severity="error" size="small" variant="simple">
                                {{ $form.email.error?.message }}
                            </Message>
                        </div>
                        <div class="flex flex-col">
                            <label for="password">Password</label>
                            <Password name="password" v-model="form.password" :feedback="false" fluid toggleMask />
                            <Message v-if="$form.password?.invalid" severity="error" size="small" variant="simple">
                                <ul class="my-0 px-4 flex flex-col gap-1">
                                    <li v-for="(error, index) of $form.password.errors" :key="index">
                                        {{ error.message }}</li>
                                </ul>
                            </Message>

                        </div>
                    </div>
                    <Button type="submit" severity="secondary" label="Submit" :disabled="form.processing" />
                </Form>
            </div>
        </div>
    </div>
</template>
