<script setup>
import { onMounted } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

onMounted(() => {
    // Prevent background scrolling when modal is open
    document.body.style.overflow = 'hidden';
});

function goBack() {
    // Redirect the user to the login page
    router.push('/dashboard');
}
</script>

<template>
    <div class="fixed inset-0 flex items-center justify-center z-10 overflow-hidden">
        <!-- Modal -->
        <!-- class="bg-gradient-to-b from-[#f9fffe] to-[#f4fefc] w-1/3 p-8 rounded-2xl shadow-xl flex flex-col items-center" -->
        <div
            class="w-1/3 p-8 rounded-2xl flex flex-col items-center">
            <h2 class="text-2xl font-bold mb-4">404 - Page Not Found</h2>
            <p class="text-gray-600 mb-8">Please contact the administrator.</p>
            <button @click="goBack" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg">
                Go Back
            </button>
        </div>
        <!-- End of Modal -->
    </div>
</template>

<style>
.shadow-xl {
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
}
</style>
