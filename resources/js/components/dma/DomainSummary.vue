<script setup>
import {computed} from "vue";

import imgLeading from '@/assets/images/dma/illustration-leading.png';
import imgLearning from '@/assets/images/dma/illustration-learning.png';
import imgManaging from '@/assets/images/dma/illustration-managing.png';
import imgTeaching from '@/assets/images/dma/illustration-teaching.png';
import {useDomainDescription} from "@/js/components/dma/domainHelper";
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

</script>

<template>
    <button
        class="bg-gray-500 domain-summary flex justify-start items-center flex-row gap-10 p-10 rounded-3xl text-white"
        :class="`DomainSummary-bg-${props.domain.domain}`"
        @click="emit('click')"
    >
        <div class="flex shrink-0 justify-center items-center w-44">
            <img :src="domainImages[props.domain.domain]">
        </div>
        <div class="flex content flex-col h-full text-left w-full">
            <div class="mb-1 text-h3-caps">
                {{ props.domain.domain }}
            </div>
            <div class="flex-1 font-medium mb-8 opacity-60 text-medium">
                {{ useDomainDescription(props.domain.domain) }}
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
                <div class="font-bold progress-value text-medium">
                    {{ props.domain.completed_chapter_count }}/{{ props.domain.chapter_count }}
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
