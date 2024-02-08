<script setup>

import {computed, onBeforeUnmount, onMounted, ref} from "vue";

import AnswerButton from "@/js/components/dma/AnswerButton.vue";
import PrimaryActionButton from "@/js/components/dma/PrimaryActionButton.vue";
import Spinner from "@/js/components/spinner/Spinner.vue";

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

const domainName = computed(() => {
    return props.domain.domain.toLowerCase();
})

// chapters contains the list of chapter names in this domain, and their current progress status
const chapters = computed(() => {
    let currentQuestionIndex = props.questions.findIndex(q => q.id === props.domain.next_question_id);
    if (currentQuestionIndex < 0) currentQuestionIndex = 0;

    const chapterList = [];
    for (let index = 0; index < props.questions.length; ++index) {
        const question = props.questions[index];
        let chapterItem = chapterList.find(item => item.name === question.chapter);
        if (!chapterItem) {
            const status = (index < currentQuestionIndex ? 'complete' : 'incomplete');
            chapterItem = { name: question.chapter, status};
            chapterList.push(chapterItem);
        }
        if (index === currentQuestionIndex) chapterItem.status = 'active';
    }
    return chapterList;
})

</script>

<template>
    <div
        class="flex justify-start items-center flex-row h-full question-screen w-full"
    >
        <div
            class="basis-2/3 flex flex-col h-full p-10 pt-28"
            :class="`bg-${domainName}-flat`"
        >
            <div class="flex justify-center items-center flex-1">
                [launch video]
            </div>
            <div>
                <h2
                    class="font-black text-4xl uppercase"
                >
                    {{ domain.domain }}
                </h2>
                <p class="font-light mt-10 w-2/3">
                    {{ domain.description }}
                </p>
            </div>
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
            <div>
                <PrimaryActionButton
                    :disabled="!questions"
                    @click="emit('continue')"
                >
                    Continue
                </PrimaryActionButton>
            </div>
        </div>
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
