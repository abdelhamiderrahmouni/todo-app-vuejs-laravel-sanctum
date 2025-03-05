// stores/auth.js
import { defineStore } from 'pinia';
import axios from 'axios';
import router from "@/router.js";

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: localStorage.getItem('token') || null,
    }),

    getters: {
        isAuthenticated: (state) => !!state.token,
    },

    actions: {
        setAuthHeader(token) {
            if (token) {
                window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
            } else {
                delete window.axios.defaults.headers.common['Authorization'];
            }
        },

        async register(userData) {
            try {
                const response = await axios.post('/api/register', userData)
                this.token = response.data.token;
                this.user = response.data.user;
                this.setAuthHeader(this.token);
                localStorage.setItem('token', this.token);

                return true;
            } catch (error) {
                console.error('Register failed:', error);
                throw error
            }
        },

        async login(credentials) {
            try {
                const response = await axios.post('/api/login', credentials);
                this.token = response.data.token;
                this.user = response.data.user;
                this.setAuthHeader(this.token);
                localStorage.setItem('token', this.token);

                return true;
            } catch (error) {
                console.error('Login failed:', error);
                return false;
            }
        },

        async fetchUser() {
            if (!this.token) return;

            if (this.user)
                return;

            try {
                const response = await axios.get('/api/user');
                this.user = response.data;
            } catch (error) {
                this.clearAuthData();
                throw error;
            }
        },

        async logout() {
            try {
                if (this.token) {
                    await axios.post('/api/logout', {});

                    router.push('/login');
                }
            } catch (error) {
                console.error('Logout error:', error);
            } finally {
                this.clearAuthData();
            }
        },

        clearAuthData() {
            this.user = null;
            this.token = null;
            localStorage.removeItem('token');
            this.setAuthHeader(null);
        }
    }
});
