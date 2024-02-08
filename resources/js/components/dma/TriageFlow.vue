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
    domainId: {
        type: [String, Number],
        required: true,
    }
})

const emit = defineEmits(['complete']);

// triage data from API
const triage = ref(null);
// current triage question index
// (triage is always done in its entirety so don't care about question IDs)
const question = ref(null);



onMounted(async () => {
    // get triage questions for the current survey
    triage.value = await dmaService.getQuestions(props.surveyId, props.domainId);
});

const handleAnswer = (answer) => {
    if (question.value < triage.value.domain_questions.length -1) {
        // TODO submit answer
        question.value += 1;
    } else {
        emit('complete');
    }
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
                <div class="text-center">
                    <h1>
                        <span class="font-black text-4xl uppercase">Digital Maturity</span><br>
                        <span class="font-black text-6xl uppercase">Assessment</span>
                    </h1>
                    <p class="mt-10">
                        Before you get started, please<br>answer a few quick questions
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
            @previous="handlePrevious"
            @answer="handleAnswer"
        >
            <template #contentTop>
                <h2
                    class="font-black text-3xl uppercase"
                >
                    General Questions
                </h2>
            </template>
            <template #question>
                <span>{{ triage.domain_questions[question].question }}</span>
            </template>
            <template
                v-if="triage.domain_questions[question].question_example"
                #info
            >
                {{ triage.domain_questions[question].question_example }}
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
