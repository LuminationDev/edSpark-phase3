<script setup lang="ts">

import {ref} from "vue";

import iconChevronRight from '@/assets/images/dma/icons/chevron-right.svg';

const showAnswer = ref(false);
</script>
<template>
    <button
        class="border border-main-darkTeal faq-entry p-6 md:p-8"
        @click="showAnswer = !showAnswer"
    >
        <div class="flex justify-between items-center question-row">
            <div class="text-large text-left">
                <slot name="question" />
            </div>
            <div class="text-large toggle">
                <div
                    class="flex justify-center items-center h-8 w-8"
                >
                    <img
                        :src="iconChevronRight"
                        :class="showAnswer ? `rotate-90` : `rotate-0`"
                    >
                </div>
            </div>
        </div>
        <div
            v-if="showAnswer"
            class="font-thin mt-5 text-left"
        >
            <slot name="answer" />
        </div>
    </button>
</template>
