<script setup>
import {computed, onMounted, ref, toRaw} from "vue";

import CoverScreen from "@/js/components/dma/CoverScreen.vue";
import DomainCoverScreen from "@/js/components/dma/DomainCoverScreen.vue";
import ProgressBar from "@/js/components/dma/ProgressBar.vue";
import QuestionScreen from "@/js/components/dma/QuestionScreen.vue";
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


onMounted(async () => {
    // get domain questions for the current survey
    const domainData = await dmaService.getQuestions(props.surveyId, props.domain.id);
    if(domainData) questions.value = domainData.domain_questions;
});

const domainName = computed(() => {
    return props.domain.domain.toLowerCase();
})

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

const getNextQuestionId = (nextChapter = false) => {
    if (questionId.value === null) {
        handleContinueSurvey();
        return questionId.value;
    }
    // find the index of the current question
    const currentIndex = questions.value.findIndex(q => q.id === questionId.value);
    let nextQuestion = null;
    if (currentIndex < questions.value.length -1) {
        if (nextChapter) {
            // if requested, skip to next chapter
            nextQuestion = questions.value.find((q, index) => index > currentIndex && q.chapter !== currentQuestion.value.chapter);
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
    return nextQuestion.id;
}

const handleAnswer = (answer) => {
    const nextQuestionId = getNextQuestionId(answer === 0);
    // TODO submit answer to API with next question ID
    questionId.value = nextQuestionId;
}

const handlePrevious = () => {
    // can only go to previous question in current session
}

const handleResetDomain = () => {
    // TODO perform domain reset here
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
                :theme="domainName"
                corner-controls
                @primary="questionId = getNextQuestionId()"
                @secondary="questionId = null"
            >
                <template #content>
                    <div class="">
                        <div class="font-black font-bold text-xl uppercase">
                            Chapter {{ chapters.indexOf(currentQuestion.chapter)+1 }}/{{ chapters.length }}
                        </div>
                        <h2 class="font-black text-6xl uppercase">
                            {{ currentQuestion.chapter }}
                        </h2>
                        <p class="mt-10">
                            {{ currentQuestion.description }}
                        </p>
                    </div>
                </template>
                <template #primaryAction>
                    Continue
                </template>
                <template #secondaryAction>
                    Previous
                </template>
            </CoverScreen>
            <QuestionScreen
                v-else
                :theme="domainName"
                :show-previous="false"
                @previous="handlePrevious"
                @answer="handleAnswer"
            >
                <template #contentTop>
                    <div class="font-black font-bold uppercase">
                        Chapter {{ chapters.indexOf(currentQuestion.chapter)+1 }}/{{ chapters.length }}
                    </div>
                    <h3 class="font-black text-3xl uppercase">
                        {{ currentQuestion.chapter }}
                    </h3>
                    <p class="font-light mt-10">
                        {{ currentQuestion.category }}
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
                    <span>{{ currentQuestion.question }}</span>
                </template>
                <template
                    v-if="currentQuestion?.question_example"
                    #info
                >
                    {{ currentQuestion.question_example }}
                </template>
            </QuestionScreen>
        </template>
        <CoverScreen
            v-else
            :theme="domainName"
            @primary="emit('complete')"
            @secondary="handleResetDomain"
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
    </template>
    <div
        v-else
        class="flex justify-center items-center h-full w-full"
    >
        <Spinner />
    </div>
</template>
