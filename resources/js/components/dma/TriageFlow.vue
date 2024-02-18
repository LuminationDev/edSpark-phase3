<script setup>
import {onMounted, ref} from "vue";

import CoverScreen from "@/js/components/dma/CoverScreen.vue";
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

const emit = defineEmits(['complete', 'error']);

// triage data from API
const triage = ref(null);
// current triage question index
const question = ref(null);
// submitting is true while an answer is being submitted
const submitting = ref(false);


onMounted(async () => {
    // get triage questions for the current survey
    dmaService.getQuestions(props.domain.id).then((result) => {
        triage.value = result;
    }).catch((error) => {
        console.log("API error getting triage questions:", error);
        emit('error');
    })
});

const handleAnswer = async (answer) => {
    submitting.value = true;
    const nextQuestionId =
        question.value < triage.value.domain_questions.length -1
            ? triage.value.domain_questions[question.value + 1].id
            : null;

    // submit answer to API
    dmaService.postAnswer(
        props.domain.id,
        triage.value.domain_questions[question.value].id,
        answer,
        null,
        nextQuestionId,
        nextQuestionId === null
    ).then((result) => {
        console.log('triage answer submitted:', result);
        if(nextQuestionId !== null) {
            question.value += 1;
        } else {
            emit('complete');
        }
        submitting.value = false;
    }).catch((error) => {
        console.log("API error submitting triage answer:", error);
        emit('error');
    })
}

const handlePrevious = () => {
    question.value -= 1;
}

</script>

<template>
    <template v-if="triage">
        <CoverScreen
            v-if="question === null"
            theme="triage"
            @primary="() => question = 0"
        >
            <template #content>
                <div class="flex items-center flex-col text-center">
                    <h1 class="text-h3-caps">
                        <span>Digital Maturity</span><br>
                        <span class="text-h1-caps">Assessment</span>
                    </h1>
                    <p class="max-w-[400px] mt-9 text-large">
                        Before you get started, please answer a few quick questions
                    </p>
                </div>
            </template>
            <template #primaryAction>
                Start
            </template>
        </CoverScreen>
        <QuestionScreen
            v-else
            theme="triage"
            :allow-unsure="false"
            :show-previous="question > 0"
            :disabled="submitting"
            @previous="handlePrevious"
            @answer="handleAnswer"
        >
            <template #contentTop>
                <h2
                    class="text-h3-caps"
                >
                    General Questions
                </h2>
            </template>
            <template #question>
                <div v-html="triage.domain_questions[question].question" />
            </template>
            <template
                v-if="triage.domain_questions[question].question_example"
                #info
            >
                <div v-html="triage.domain_questions[question].question_example" />
            </template>
        </QuestionScreen>
    </template>
    <div
        v-else
        class="flex justify-center items-center h-full w-full"
    >
        <Spinner />
    </div>
</template>


<style scoped>
    h1 {
        line-height: 1.1;
    }
    h1 span:first-child {
        font-size: 1.15em;
        letter-spacing: 0.2em;
    }
</style>
