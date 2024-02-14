<script setup lang="ts">

import {ref} from "vue";

import iconChevronRight from '@/assets/images/dma/icons/chevron-right.svg';

const showAnswer = ref(false);
</script>
<template>
    <button
        class="block border border-main-darkTeal faq-entry mt-5 p-8 w-full"
        @click="showAnswer = !showAnswer"
    >
        <div class="flex justify-between items-center question-row">
            <div class="text-large">
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
            class="font-light mt-5 text-base text-left"
        >
            <slot name="answer" />
        </div>
    </button>
</template>
