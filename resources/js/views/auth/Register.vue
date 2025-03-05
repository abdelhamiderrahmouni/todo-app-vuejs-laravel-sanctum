<script setup>
import axios from "axios";
import {ref} from "vue";
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const router = useRouter();
const authStore = useAuthStore();

const form = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
});

const errors = ref({});

// listeners
async function register() {
    try {
        errors.value = {};
        if (await authStore.register(form.value))
            router.push('/');
    } catch (error) {
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors;
        }
    }
}
</script>

<template>
    <main class="flex min-h-screen items-center justify-center">
        <div class="w-full max-w-md p-8 space-y-8 bg-white rounded-xl shadow">
            <h1 class="text-2xl font-bold text-center">Register</h1>

            <form @submit.prevent="register" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input
                        type="text"
                        v-model="form.name"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        required
                    />
                    <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name[0] }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input
                        type="email"
                        v-model="form.email"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        required
                    />
                    <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email[0] }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input
                        type="password"
                        v-model="form.password"
                        autocomplete="new-password"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        required
                    />
                    <p v-if="errors.password" class="mt-1 text-sm text-red-600">{{ errors.password[0] }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Password confirmation</label>
                    <input
                        type="password"
                        v-model="form.password_confirmation"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        required
                    />
                </div>

                <div>
                    <button
                        type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Register
                    </button>
                </div>
            </form>

            <div class="text-center">
                <router-link to="/login" class="text-sm text-indigo-600 hover:text-indigo-500">
                    Already have an account ? Login !
                </router-link>
            </div>
        </div>
    </main>
</template>
