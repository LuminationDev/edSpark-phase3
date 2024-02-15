<script setup>

import {computed, ref} from "vue";

import iconCheck from '@/assets/images/dma/icons/check.svg';
import iconPlay from '@/assets/images/dma/icons/play.svg';
import vidTeaching from '@/assets/video/temporary.mp4';
import vidLeading from '@/assets/video/temporary.mp4';
import vidLearning from '@/assets/video/temporary.mp4';
import vidManaging from '@/assets/video/temporary.mp4';
import OverlayModal from "@/js/components/dma/OverlayModal.vue";
import PlayButton from "@/js/components/dma/PlayButton.vue";
import PrimaryActionButton from "@/js/components/dma/PrimaryActionButton.vue";
import TextButton from "@/js/components/dma/TextButton.vue";
import VideoModal from "@/js/components/dma/VideoModal.vue";
import WarningModal from "@/js/components/dma/WarningModal.vue";
import Spinner from "@/js/components/spinner/Spinner.vue";

import { useDomainDescription } from "./domainHelper";

const props = defineProps({
    domain: {
        type: Object,
        required: true,
    },
    questions: {
        type: Array,
        required: false,
        default: null,
    },
})

const emit = defineEmits(['continue','reset']);

// map domains to their images
// TODO insert video paths when available
const domainVideos = {
    teaching: vidTeaching,
    learning: vidLearning,
    leading: vidLeading,
    managing: vidManaging
};

const showResetModal = ref(false);
const showVideoModal = ref(false);

const handleResetDomain = () => {
    showResetModal.value = false;
    emit('reset');
}

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
        class="flex md:flex-row justify-start items-center flex-col h-full question-screen w-full"
    >
        <div
            class="flex-col h-full hidden p-14 pt-28 relative md:!flex md:basis-3/5 lg:!basis-2/3"
            :class="`DomainCover-bg-${props.domain.domain}`"
        >
            <div
                class="flex justify-center items-center flex-1 lg:min-h-[300px]"
            >
                <PlayButton
                    v-if="domainVideos[domain.domain] !== null && !showVideoModal"
                    @click="showVideoModal = true"
                />
            </div>
            <div>
                <h2
                    class="text-h2-caps"
                >
                    {{ domain.domain }}
                </h2>
                <p class="mt-5 text-medium w-2/3">
                    {{ useDomainDescription(domain.domain) }}
                </p>
            </div>
            <VideoModal
                v-if="showVideoModal"
                :src="domainVideos[domain.domain]"
                @close="showVideoModal = false"
            />
        </div>
        <div
            class="bg-black flex flex-col min-h-full p-10 w-full md:!py-16 md:basis-2/5 md:h-full lg:basis-1/3"
        >
            <div class="mb-10 mt-20 text-center md:!mt-0">
                <h3 class="text-h2 md:text-h3">
                    <span class="text-h5-caps">
                        Domain
                    </span> <br>
                    Breakdown
                </h3>
            </div>
            <div
                class="flex justify-center items-center flex-1 transition-opacity"
                :class="`${questions ? 'opacity-100' : 'opacity-0'}`"
            >
                <div
                    v-if="questions"
                    class="chapter-progress flex flex-col gap-8"
                >
                    <div
                        v-for="(chapter, index) of chapters"
                        :key="index"
                        class="flex items-center gap-5 text-large"
                    >
                        <div
                            v-if="chapter.status === 'complete'"
                            class="complete status-mark"
                        >
                            <img :src="iconCheck">
                        </div>
                        <div
                            v-else-if="chapter.status === 'active'"
                            class="current status-mark"
                        />
                        <div
                            v-else
                            class="incomplete status-mark"
                        />
                        {{ chapter.name }}
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-7 md:gap-10 mt-10 text-center">
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
                    <div
                        class="flex justify-center items-center font-semibold gap-2 text-medium"
                    >
                        <img
                            :src="iconCheck"
                            class="opacity-60"
                        > Completed
                    </div>
                </div>
                <TextButton
                    class="text-small"
                    @click="showResetModal = true"
                >
                    Reset progress
                </TextButton>
            </div>
        </div>
        <WarningModal
            v-if="showResetModal"
            embed
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

.chapter-progress {
    position: relative;
    padding: 0.5rem 0px;

    &:before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0px;
        bottom: 1rem;
        width: 1px;
        background: rgba(255, 255, 255, 0.3);
    }
}

.status-mark {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.16);
    backdrop-filter: blur(20px);
    display:flex;
    align-items: center;
    justify-content: center;
    position: relative;

    img {
        width: 16px;
        height: 16px;
    }

    &.current {
        background: white;
    }

    &.incomplete:after {
        content: '';
        position: absolute;
        inset: 5px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
    }
}
</style>
