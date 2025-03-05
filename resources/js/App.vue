<script setup>
import { onBeforeMount } from 'vue';
import { RouterLink, RouterView } from 'vue-router'
import { useAuthStore } from './stores/auth';
import { useRouter } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();

onBeforeMount(async () => {
    if (authStore.token) {
        try {
            await authStore.fetchUser();
        } catch (error) {
            authStore.clearAuthData();
            await router.push('/login');
        }
    }
});
</script>

<template>
    <header class="bg-gray-100 text-gray-800">
        <div class="max-w-7xl mx-auto px-4 flex items-center justify-between py-2">
            <img class="w-10" alt="Vue logo" src="@/assets/logo.svg" width="125" height="125" />

            <div>
                <nav class="flex items-center space-x-4">
                    <RouterLink activeClass="text-red-600" to="/">Home</RouterLink>
                    <RouterLink activeClass="text-red-600" to="/about">About</RouterLink>
                    <span>|</span>
                    <template v-if="!authStore.isAuthenticated">
                        <RouterLink activeClass="text-red-600" to="/login">Login</RouterLink>
                        <RouterLink activeClass="text-red-600" to="/register">Register</RouterLink>
                    </template>

                    <template v-else>
                        <span>{{ authStore.user?.name }}</span>
                        <button @click="authStore.logout" class="ml-2 text-red-600">Logout</button>
                    </template>
                </nav>
            </div>
        </div>
    </header>

    <RouterView />

    <footer>
        <div class="max-w-7xl mx-auto px-4 py-4 text-center">
            <p>&copy; 2025 Todo list App</p>
        </div>
    </footer>
</template>
