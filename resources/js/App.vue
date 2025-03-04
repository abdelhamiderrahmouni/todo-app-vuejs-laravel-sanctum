<script setup>
import { RouterLink, RouterView } from 'vue-router'

</script>

<template>
    <header class="bg-gray-100 text-gray-800">
        <div class="max-w-7xl mx-auto px-4 flex items-center justify-between py-2">
            <img class="w-10" alt="Vue logo" src="@/assets/logo.svg" width="125" height="125" />

            <div>
                <HelloWorld msg="You did it!" />

                <nav class="flex items-center space-x-4">
                    <RouterLink activeClass="text-red-600" to="/">Home</RouterLink>
                    <RouterLink activeClass="text-red-600" to="/about">About</RouterLink>
                </nav>
            </div>
        </div>
    </header>

    <RouterView />
</template>
