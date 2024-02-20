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

const emit = defineEmits(['complete','reset','error']);

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
    dmaService.getQuestions(props.domain.id).then((result) => {
        if (result) questions.value = result.domain_questions;
    }).catch((error) => {
        console.log("API error getting domain questions:", error);
        emit('error');
    })
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

const elements = computed(() => {
    if (!questions.value) return [];
    return questions.value.reduce((list, question) => {
        if (!list.includes(question.element)) {
            list.push(question.element);
        }
        return list;
    }, []);
})

const handleContinueSurvey = () => {
    // continue survey from the next question ID
    questionId.value = props.domain.next_question_id;
}

const getNextQuestionId = (nextIndicator = false) => {
    if (questionId.value === null) {
        handleContinueSurvey();
        return questionId.value;
    }
    // find the index of the current question
    const currentIndex = questions.value.findIndex(q => q.id === questionId.value);
    let nextQuestion = null;
    if (currentIndex < questions.value.length -1) {
        if (nextIndicator) {
            // if requested, skip to next indicator
            nextQuestion = questions.value.find((q, index) => {
                return index > currentIndex && q.indicator !== currentQuestion.value.indicator
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

    // check if element is complete
    let elementComplete = false;
    if (nextQuestionId === null) {
        // end of domain
        elementComplete = true;
    } else {
        const nextQuestion = questions.value.find(q => q.id === nextQuestionId);
        if (!nextQuestion || nextQuestion.element !== currentQuestion.value.element) {
            // next question starts a new element
            elementComplete = true;
        }
    }

    // submit answer to API
    dmaService.postAnswer(
        props.domain.id,
        questionId.value,
        answer,
        answerText,
        nextQuestionId,
        elementComplete
    ).then(() => {
        previousQuestionId.value = questionId.value;
        questionId.value = nextQuestionId;
        submitting.value = false;
    }).catch((error) => {
        console.log("API error submitting answer:", error);
        emit('error');
    });
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
                    <div
                        class="w-full"
                    >
                        <div class="mb-2 text-h5-caps">
                            Element {{ elements.indexOf(currentQuestion.element) + 1 }}/{{ elements.length }}
                        </div>
                        <h2 class="text-h1-caps md:text-h2-caps lg:text-h1-caps">
                            {{ currentQuestion.element_print }}
                        </h2>
                        <div
                            class="max-w-[900px] mt-6 md:mt-10 text-base md:text-medium lg:text-large"
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
                    <div class="text-h5-caps">
                        Element {{ elements.indexOf(currentQuestion.element) + 1 }}/{{ elements.length }}
                    </div>
                    <h3 class="mt-1 text-h3-caps">
                        {{ currentQuestion.element_print }}
                    </h3>
                    <p class="mt-7 text-medium">
                        {{ currentQuestion.indicator_print }}
                    </p>
                </template>
                <template #contentBottom>
                    <h2
                        class="hidden mb-5 text-h4-caps md:!block"
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
                <div class="flex items-center flex-col text-center">
                    <h1 class="text-h3-caps">
                        <span>Domain</span><br>
                        <span class="text-h1-caps">Complete</span>
                    </h1>
                    <p class="max-w-[400px] mt-9 text-medium md:text-large">
                        You did great. Let's start another domain when you're ready.
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
    </template>
    <div
        v-else
        class="flex justify-center items-center h-full w-full"
    >
        <Spinner />
    </div>
</template>
