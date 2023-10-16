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
    router.push('/');
}
</script>

<template>
    <div class="fixed inset-0 flex justify-center items-center overflow-hidden z-10">
        <!-- Modal -->
        <!-- class="bg-gradient-to-b from-[#f9fffe] to-[#f4fefc] w-1/4 p-8 rounded-2xl shadow-xl flex flex-col items-center" -->
        <div
            class="flex items-center flex-col p-8 rounded-2xl w-1/4"
        >
            <h2 class="font-bold mb-4 text-2xl">
                Access Denied
            </h2>
            <p class="mb-8 text-gray-600">
                You do not have permission to access this page.
            </p>
            <button
                class="bg-blue-500 hover:bg-blue-600 font-bold px-4 py-2 rounded-lg text-white"
                @click="goBack"
            >
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
