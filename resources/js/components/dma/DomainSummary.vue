<script setup>
import {computed} from "vue";

import imgLeading from '@/assets/images/dma/Leading.png';
import imgLearning from '@/assets/images/dma/Learning.png';
import imgManaging from '@/assets/images/dma/Managing.png';
import imgTeaching from '@/assets/images/dma/Teaching.png';
import ProgressBar from "@/js/components/dma/ProgressBar.vue";

const props = defineProps({
    domain: {
        type: Object,
        required: true,
    },
    resetting: {
        type: Boolean,
        required: false,
    }
})

const emit = defineEmits(['click']);

// map domains to their images
const domainImages = {
    teaching: imgTeaching,
    learning: imgLearning,
    leading: imgLeading,
    managing: imgManaging
};

const progressPercent = computed(() => {
    return props.domain.completed_question_count / props.domain.question_count * 100;
})

const domainName = computed(() => {
    return props.domain.domain.toLowerCase();
})

</script>

<template>
    <button
        class="bg-gray-500 domain-summary flex justify-start items-center flex-row gap-5 p-5 rounded-3xl text-white"
        :class="`bg-${domainName}-flat`"
        @click="emit('click')"
    >
        <div class="flex justify-center items-center w-48">
            <img :src="domainImages[domainName]">
        </div>
        <div class="flex content flex-col h-full text-left">
            <div class="font-black mb-1 text-2xl uppercase">
                {{ props.domain.domain }}
            </div>
            <div class="flex-1 font-base opacity-70 text-lg">
                {{ props.domain.description }}
            </div>
            <div
                v-if="props.resetting"
                class="font-bold"
            >
                ✓ Domain reset
            </div>
            <div
                v-else-if="progressPercent < 100"
                class="flex items-center flex-row gap-5 progress"
            >
                <div class="bg-white/20 flex-1 h-2 progress-bar-track rounded-full">
                    <ProgressBar :percent="progressPercent" />
                </div>
                <div class="font-bold progress-value text-xl">
                    {{ props.domain.completed_chapters_count }}/{{ props.domain.chapters_count }}
                </div>
            </div>
            <div
                v-else
                class="font-bold"
            >
                ✓ Completed
            </div>
        </div>
    </button>
</template>
