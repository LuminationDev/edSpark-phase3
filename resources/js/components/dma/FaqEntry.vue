<script setup lang="ts">

import {ref} from "vue";

const showAnswer = ref(false);
</script>
<template>
    <button
        class="block border border-main-darkTeal faq-entry mt-5 p-8 w-full"
        @click="showAnswer = !showAnswer"
    >
        <div class="flex justify-between items-center question-row">
            <div class="text-2xl">
                <slot name="question" />
            </div>
            <div class="text-2xl toggle">
                <div
                    class="flex justify-center items-center h-8 w-8"
                >
                    <span v-if="showAnswer">v</span>
                    <span v-else>></span>
                </div>
            </div>
        </div>
        <div
            v-if="showAnswer"
            class="font-light mt-5 text-left"
        >
            <slot name="answer" />
        </div>
    </button>
</template>
