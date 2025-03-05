import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'


function authCheck(to, from, next) {
    const authStore = useAuthStore()
    if (!authStore.isAuthenticated) {
        return next('/login')
    }
    return next()
}

function guestCheck(to, from, next) {
    const authStore = useAuthStore()
    if (authStore.isAuthenticated) {
        return next('/')
    }
    return next()
}

const router = createRouter({
    history: createWebHistory('/'),
    routes: [
        {
            path: '/',
            name: 'home',
            component: () => import('./views/HomeView.vue'),
            beforeEnter: authCheck,
            meta: { requiresAuth: true }
        },
        {
            path: '/about',
            name: 'about',
            component: () => import('./views/AboutView.vue'),
        },
        {
            path: '/login',
            name: 'login',
            component: () => import('./views/auth/Login.vue'),
            beforeEnter: guestCheck,
            meta: { guestOnly: true }
        },
        {
            path: '/register',
            name: 'register',
            component: () => import('./views/auth/Register.vue'),
            beforeEnter: guestCheck,
            meta: { guestOnly: true }
        }
    ],
});

router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();

    // Load user if we have token but no user
    if (authStore.token && !authStore.user) {
        try {
            await authStore.fetchUser();
        } catch (error) {
            authStore.clearAuthData();
            return next({ path: '/login'});
        }
    }

    // Handle route meta requirements
    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        return next({ path: '/login'});
    }

    if (to.meta.guestOnly && authStore.isAuthenticated) {
        return next('/');
    }

    next();
});

export default router
