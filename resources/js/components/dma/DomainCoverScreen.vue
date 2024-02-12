<script setup>

import {computed, ref} from "vue";

import OverlayModal from "@/js/components/dma/OverlayModal.vue";
import PrimaryActionButton from "@/js/components/dma/PrimaryActionButton.vue";
import TextButton from "@/js/components/dma/TextButton.vue";
import WarningModal from "@/js/components/dma/WarningModal.vue";
import Spinner from "@/js/components/spinner/Spinner.vue";

import useDomainDescription from "./domainDescription";

const props = defineProps({
    domain: {
        type: Object,
        required: true,
    },
    questions: {
        type: Array,
        required: false,
        default: null,
    }
})

const emit = defineEmits(['continue','reset']);

const showResetModal = ref(false);
const showVideo = ref(false);

const handleResetDomain = () => {
    showResetModal.value = false;
    emit('reset');
}

const hideVideo =() => {
    showVideo.value = false;
}

const domainName = computed(() => {
    return props.domain.domain.toLowerCase();
})

const domainComplete = computed(() => {
    return props.domain.completed_question_count === props.domain.question_count;
})

// chapters contains the list of chapter names in this domain, and their current progress status
const chapters = computed(() => {
    let currentQuestionIndex = props.questions.findIndex(q => q.id === props.domain.next_question_id);
    if (currentQuestionIndex < 0) currentQuestionIndex = 0;

    const chapterList = [];
    for (let index = 0; index < props.questions.length; ++index) {
        const question = props.questions[index];
        let chapterItem = chapterList.find(item => item.ref === question.chapter);
        if (!chapterItem) {
            const status = ((domainComplete.value || index < currentQuestionIndex) ? 'complete' : 'incomplete');
            chapterItem = { ref: question.chapter, name: question.chapter_print, status};
            chapterList.push(chapterItem);
        }
        if (!domainComplete.value && index === currentQuestionIndex) chapterItem.status = 'active';
    }
    return chapterList;
})

</script>

<template>
    <div
        class="flex justify-start items-center flex-row h-full question-screen w-full"
    >
        <div
            class="basis-2/3 flex flex-col h-full p-10 pt-28 relative"
            :class="`bg-${domainName}-flat`"
        >
            <div class="flex justify-center items-center flex-1">
                <button
                    class="bg-black/50 flex justify-center items-center h-16 rounded-full w-16"
                    @click="showVideo = true"
                >
                    ▶
                </button>
            </div>
            <div>
                <h2
                    class="font-black text-4xl uppercase"
                >
                    {{ domain.domain }}
                </h2>
                <p class="font-light mt-10 w-2/3">
                    {{ useDomainDescription(domain.domain) }}
                </p>
            </div>
            <OverlayModal
                v-if="showVideo"
                :embed="true"
                :click-away="true"
                @close="hideVideo"
            >
                <video
                    src="@/assets/video/temporary.mp4"
                    controls
                    autoplay
                    @ended="hideVideo"
                />
            </OverlayModal>
        </div>
        <div class="basis-1/3 bg-black flex flex-col h-full p-10 py-16">
            <div class="text-center">
                <h3 class="font-black text-3xl">
                    <div class="text-lg uppercase">
                        Domain
                    </div>
                    Breakdown
                </h3>
            </div>
            <div class="flex justify-center items-center flex-1">
                <div
                    v-if="questions"
                    class="chapter-progress"
                >
                    <div
                        v-for="(chapter, index) of chapters"
                        :key="index"
                        class="my-5 text-xl"
                    >
                        <span
                            v-if="chapter.status === 'complete'"
                            class="mr-10"
                        >✓</span>
                        <span
                            v-else-if="chapter.status === 'active'"
                            class="mr-10"
                        >⬤</span>
                        <span
                            v-else
                            class="mr-10"
                        >◯</span>
                        {{ chapter.name }}
                    </div>
                </div>
                <Spinner v-else />
            </div>
            <div class="text-center">
                <div v-if="!domainComplete">
                    <PrimaryActionButton
                        :disabled="!questions"
                        @click="emit('continue')"
                    >
                        Continue
                    </PrimaryActionButton>
                </div>
                <div
                    v-else
                >
                    <div class="text-lg">
                        ✓ Completed
                    </div>
                </div>

                <TextButton
                    class="mt-10 text-xs"
                    @click="showResetModal = true"
                >
                    Reset progress
                </TextButton>
            </div>
        </div>
        <WarningModal
            v-if="showResetModal"
            @cancel="showResetModal=false"
            @reset="handleResetDomain"
        >
            <template #title>
                Are you sure?
            </template>
            <template #message>
                Resetting will erase all your progress made on this domain. Other domains won't be affected.
            </template>
        </WarningModal>
    </div>
</template>

<style scoped>
.info-tooltip {
    display: none;
}
.info-icon:hover .info-tooltip {
    display: block;
}
</style>
