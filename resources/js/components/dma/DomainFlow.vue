<script setup>
import {computed, onMounted, ref} from "vue";

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

const emit = defineEmits(['complete','reset','error']);

// question data from API
const questions = ref(null);
// question ID is null until continue event from cover
const questionId = ref(null);
// elementCompleted is true when all questions in an element have been answered
const elementCompleted = ref(null);
// completed is true when there are no more questions to answer
const completed = ref(false);
// submitting is true while an answer is being submitted
const submitting = ref(false);
// dependencies that have been met (generated_variables from answered questions)
const metDependencies = ref(props.domain.met_dependencies || []);

// when the domain is completed, the summary is displayed for reflection
const showDomainSummary = ref(false);
// reflection is the user provided feedback
const reflection = ref(null);

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
const displayElements = computed(() => {
    if (!questions.value) return [];
    return questions.value.reduce((list, question) => {
        if (!list.includes(question.element_print)) {
            list.push(question.element_print);
        }
        return list;
    }, []);
})

const handleContinueSurvey = () => {
    // continue survey from the next question ID
    questionId.value = props.domain.next_question_id;
}

const getNextQuestion = () => {
    if (questionId.value === null) {
        handleContinueSurvey();
        return questionId.value;
    }
    // find the index of the current question
    const currentIndex = questions.value.findIndex(q => q.id === questionId.value);
    let nextIndex = currentIndex + 1;
    let nextQuestion = null;

    // search ahead until the next question with all dependencies met
    while (!nextQuestion && nextIndex < questions.value.length) {
        nextQuestion = questions.value[nextIndex];
        // check question dependencies are met, excluding out-of-domain dependencies;
        // triage dependencies are assumed to be global
        // i.e. dependency must start with an element name from this domain, or 'triage'
        if (nextQuestion.dependencies) {
            const dependencies = nextQuestion.dependencies.split(' && ').filter(dep => {
                if (dep.startsWith('triage')) return true;
                for(const el of elements.value) {
                    if (dep.startsWith(el)) return true;
                }
                return false;
            });
            if (!dependencies.every(dep => metDependencies.value.includes(dep))) {
                // dependencies not met, skip and keep searching
                nextQuestion = null;
                ++nextIndex;
            }
        }
    }
    if (!nextQuestion) {
        // no more questions, domain is complete
        completed.value = true;
        return null;
    }

    return nextQuestion;
}

const handleNextQuestion = () => {
    // when advancing past a non-question (eg. section intro),
    // always answer yes to set dependencies
    handleAnswer(1);
}

const handleAnswer = async (answer, answerText = null) => {
    submitting.value = true;

    // if answer was yes:
    if (answer === 1) {
        // update met dependencies
        metDependencies.value.push(currentQuestion.value.generated_variable);
        // update results
        const result = props.domain.results.find(r =>
            r.element === currentQuestion.value.element_print &&
            r.indicator === currentQuestion.value.indicator_print);
        if(result) {
            // treat interstitial question items (phase -1) as phase 0
            result.value = Math.max(currentQuestion.value.phase, 0);
            result.description = currentQuestion.value.description;
        }
    }
    const nextQuestion = getNextQuestion();

    // check if element is complete
    if (nextQuestion === null || nextQuestion.element !== currentQuestion.value.element) {
        elementCompleted.value = currentQuestion.value.element_print;
    }
    // if domain is complete, show the summary
    if(nextQuestion === null) {
        showDomainSummary.value = true;
    }

    const nextQuestionId = nextQuestion?.id || null;

    // submit answer to API
    dmaService.postAnswer(
        props.domain.id,
        questionId.value,
        answer,
        answerText,
        nextQuestionId,
        !!elementCompleted.value
    ).then(() => {
        previousQuestionId.value = questionId.value;
        questionId.value = nextQuestionId;
        if (nextQuestion?.phase === -1) {
            // next question should be skipped over;
            // automatically submit yes and get the following answer
            return handleNextQuestion();
        }
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

const getElementResults = (element) => {
    return props.domain.results.filter(result => result.element === element);

}

const getScoreLabel = (element) => {
    // Displayed on 'element completed' results page, after answering the last question.
    // Get average score of indicators for the completed element
    const results = getElementResults(element);
    const totalScore = results.reduce((sum, result) => {
        return sum + (result.value || 1); // treat 0 as 1
    }, 0);
    const score = Math.round(totalScore / results.length);

    switch(score) {
    case 2:
        return 'Developing';
    case 3:
        return 'Achieving';
    case 4:
        return 'Excelling';
    default:
        return 'Emerging';
    }
}

const handleCloseElementSummary = (previous = false) => {
    if (previous) {
        handlePreviousQuestion();
    }
    elementCompleted.value = null;
}

const handleSubmitReflection = async () => {
    try {
        await dmaService.putReflection(props.domain.id, reflection.value);
        showDomainSummary.value = false;
    } catch(error) {
        console.log('Error submitting reflection', error);
        emit('error');
    }
}

const handleResetDomain = () => {
    emit('reset');
}
</script>

<template>
    <template v-if="props.domain">
        <CoverScreen
            v-if="elementCompleted !== null"
            :theme="props.domain.domain"
            corner-controls
            blur-bg
            @primary="handleCloseElementSummary()"
            @secondary="handleCloseElementSummary(true)"
        >
            <template #content>
                <div
                    class="flex flex-col h-full w-full"
                >
                    <h2 class="text-h2-caps md:text-h3-caps lg:text-h2-caps">
                        {{ elementCompleted }} is {{ getScoreLabel(elementCompleted) }}
                    </h2>
                    <div class="flex-1 overflow-hidden scroll-fade">
                        <div
                            class="h-full pb-10 relative z-50 md:overflow-x-none md:overflow-y-scroll"
                        >
                            <template
                                v-for="result of getElementResults(elementCompleted)"
                                :key="`${result.element}_${result.indicator}`"
                            >
                                <div
                                    class="max-w-[900px] mt-10 text-base md:text-medium lg:text-large"
                                    v-html="result.description"
                                />
                            </template>
                        </div>
                    </div>
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
        <template v-else-if="!completed">
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
            v-else-if="showDomainSummary"
            :theme="props.domain.domain"
            corner-controls
            blur-bg
            :disabled="!reflection"
            @primary="handleSubmitReflection"
        >
            <template #content>
                <div
                    class="flex flex-col h-full w-full"
                >
                    <h2 class="text-h2-caps md:text-h3-caps lg:text-h2-caps">
                        {{ props.domain.domain }}
                    </h2>
                    <div class="flex-1 overflow-hidden scroll-fade">
                        <div
                            class="h-full pb-10 relative z-50 md:overflow-x-none md:overflow-y-scroll"
                        >
                            <template
                                v-for="element of displayElements"
                                :key="`${element}`"
                            >
                                <h2 class="mt-10 text-h3-caps md:text-h4-caps lg:text-h3-caps">
                                    {{ element }} is {{ getScoreLabel(element) }}
                                </h2>
                                <template
                                    v-for="result of getElementResults(element)"
                                    :key="`${result.element}_${result.indicator}`"
                                >
                                    <div
                                        class="max-w-[900px] mt-10 text-base md:text-medium lg:text-large"
                                        v-html="result.description"
                                    />
                                </template>
                            </template>

                            <textarea
                                v-model="reflection"
                                rows="7"
                                :disabled="props.disabled"
                                class="
                                    bg-black/50
                                    border-none
                                    max-md:h-96
                                    mt-10
                                    px-6
                                    py-5
                                    resize-none
                                    rounded-2xl
                                    text-medium
                                    focus:outline-none
                                    focus:ring
                                    md:!px-8
                                    md:!py-7
                                    "
                                placeholder="Add your reflection here.
Does this result align with your expectations?
What practices are working well and why?
What areas of success have been highlighted?
What aspects need to be prioritised?
In what way will existing practices and ways of working need to change?
                                "
                            />
                        </div>
                    </div>
                </div>
            </template>
            <template #primaryAction>
                Continue
            </template>
        </CoverScreen>
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

<style scoped lang="scss">
@media screen and (min-width: 768px) {
    .scroll-fade {
        mask-image: linear-gradient(transparent, black 5%, black 95%, transparent);
    }
}
</style>
