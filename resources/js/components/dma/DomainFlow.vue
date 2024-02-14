<script setup>
import {computed, onMounted, ref} from "vue";

import CoverScreen from "@/js/components/dma/CoverScreen.vue";
import DomainCoverScreen from "@/js/components/dma/DomainCoverScreen.vue";
import ProgressBar from "@/js/components/dma/ProgressBar.vue";
import QuestionScreen from "@/js/components/dma/QuestionScreen.vue";
import WarningModal from "@/js/components/dma/WarningModal.vue";
import Spinner from "@/js/components/spinner/Spinner.vue";
import {dmaService} from "@/js/service/dmaService";

const props = defineProps({
    surveyId: {
        type: [String, Number],
        required: true,
    },
    domain: {
        type: Object,
        required: true,
    }
})

const emit = defineEmits(['complete','reset']);

// question data from API
const questions = ref(null);
// question ID is null until continue event from cover
const questionId = ref(null);
// completed is true when there are no more questions to answer
const completed = ref(false);
// submitting is true while an answer is being submitted
const submitting = ref(false);

const showResetModal = ref(false);

const previousQuestionId = ref(null);

onMounted(async () => {
    // get domain questions for the current survey
    const domainData = await dmaService.getQuestions(props.domain.id);
    if(domainData) questions.value = domainData.domain_questions;
});

const currentQuestion = computed(() => {
    if (!questions.value) return null;
    return questions.value.find(q => {
        return q.id === questionId.value
    });
});

const domainProgress = computed(() => {
    if (!questions.value) return 0;
    const questionNumber = questions.value.findIndex(q => q.id === questionId.value);
    if (questionNumber < 0) return 0;
    return questionNumber / questions.value.length * 100;
});

const chapters = computed(() => {
    if (!questions.value) return [];
    return questions.value.reduce((list, question) => {
        if (!list.includes(question.chapter)) {
            list.push(question.chapter);
        }
        return list;
    }, []);
})

const handleContinueSurvey = () => {
    // continue survey from the next question ID
    questionId.value = props.domain.next_question_id;
}

const getNextQuestionId = (nextCategory = false) => {
    if (questionId.value === null) {
        handleContinueSurvey();
        return questionId.value;
    }
    // find the index of the current question
    const currentIndex = questions.value.findIndex(q => q.id === questionId.value);
    let nextQuestion = null;
    if (currentIndex < questions.value.length -1) {
        if (nextCategory) {
            // if requested, skip to next category
            nextQuestion = questions.value.find((q, index) => {
                return index > currentIndex && q.category !== currentQuestion.value.category
            });
        } else {
            // otherwise, get the following question
            nextQuestion = questions.value[currentIndex + 1];
        }
    }
    if (!nextQuestion) {
        // no more questions, domain is complete
        completed.value = true;
        return null;
    }
    // skip non-question entries
    // (should be safe to skip ahead as there should always be a following question)
    if (nextQuestion.phase === -1) {
        const nextIndex = questions.value.findIndex(q => q.id === nextQuestion.id);
        nextQuestion = questions.value[nextIndex + 1];
    }

    return nextQuestion.id;
}

const handleNextQuestion = () => {
    previousQuestionId.value = questionId.value;
    questionId.value = getNextQuestionId();
}

const handleAnswer = async (answer, answerText = null) => {
    submitting.value = true;
    const nextQuestionId = getNextQuestionId(answer === 0);

    // check if chapter is complete
    let chapterComplete = false;
    if (nextQuestionId === null) {
        // end of domain
        chapterComplete = true;
    } else {
        const nextQuestion = questions.value.find(q => q.id === nextQuestionId);
        if (!nextQuestion || nextQuestion.chapter !== currentQuestion.value.chapter) {
            // next question starts a new chapter
            chapterComplete = true;
        }
    }

    // submit answer to API
    const result = await dmaService.postAnswer(
        props.domain.id,
        questionId.value,
        answer,
        answerText,
        nextQuestionId,
        chapterComplete
    );
    console.log("Answer submitted:", result);

    previousQuestionId.value = questionId.value;
    questionId.value = nextQuestionId;

    submitting.value = false;
}

const handlePreviousQuestion = () => {
    if (previousQuestionId.value) {
        questionId.value = previousQuestionId.value;
        previousQuestionId.value = null;
    }
}

const handleResetDomain = () => {
    emit('reset');
}

</script>

<template>
    <template v-if="domain">
        <template v-if="!completed">
            <DomainCoverScreen
                v-if="questionId === null"
                :domain="domain"
                :questions="questions"
                @continue="handleContinueSurvey"
                @reset="handleResetDomain"
            />
            <CoverScreen
                v-else-if="currentQuestion.phase === 0"
                :theme="props.domain.domain"
                corner-controls
                blur-bg
                @primary="handleNextQuestion"
                @secondary="handlePreviousQuestion"
            >
                <template #content>
                    <div>
                        <div class="text-h5-caps">
                            Chapter {{ chapters.indexOf(currentQuestion.chapter)+1 }}/{{ chapters.length }}
                        </div>
                        <h2 class="text-h1-caps">
                            {{ currentQuestion.chapter_print }}
                        </h2>
                        <div
                            class="mt-10 text-large"
                            v-html="currentQuestion.description"
                        />
                    </div>
                </template>
                <template #primaryAction>
                    Continue
                </template>
                <template
                    v-if="previousQuestionId"
                    #secondaryAction
                >
                    Previous
                </template>
            </CoverScreen>
            <QuestionScreen
                v-else
                :theme="props.domain.domain"
                :show-previous="!!previousQuestionId"
                :disabled="submitting"
                blur-bg
                @previous="handlePreviousQuestion"
                @answer="handleAnswer"
            >
                <template #contentTop>
                    <div class="font-black font-bold uppercase">
                        Chapter {{ chapters.indexOf(currentQuestion.chapter)+1 }}/{{ chapters.length }}
                    </div>
                    <h3 class="font-black text-3xl uppercase">
                        {{ currentQuestion.chapter_print }}
                    </h3>
                    <p class="font-light mt-10">
                        {{ currentQuestion.category_print }}
                    </p>
                </template>
                <template #contentBottom>
                    <h2
                        class="font-black text-4xl uppercase"
                    >
                        {{ domain.domain }}
                    </h2>
                    <ProgressBar :percent="domainProgress" />
                </template>
                <template #question>
                    <div v-html="currentQuestion.question" />
                </template>
                <template
                    v-if="currentQuestion?.question_example"
                    #info
                >
                    <div v-html="currentQuestion.question_example" />
                </template>
            </QuestionScreen>
        </template>
        <CoverScreen
            v-else
            :theme="props.domain.domain"
            @primary="emit('complete')"
            @secondary="showResetModal = true"
        >
            <template #content>
                <div class="text-center">
                    <h1>
                        <span class="font-black text-4xl uppercase">Domain</span><br>
                        <span class="font-black text-6xl uppercase">Complete</span>
                    </h1>
                    <p class="mt-10">
                        You did great. Let's start another<br>
                        domain when you're ready.
                    </p>
                </div>
            </template>
            <template #primaryAction>
                Finish
            </template>
            <template #secondaryAction>
                Reset progress
            </template>
        </CoverScreen>
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
    </template>
    <div
        v-else
        class="flex justify-center items-center h-full w-full"
    >
        <Spinner />
    </div>
</template>
