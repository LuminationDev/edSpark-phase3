<script setup>
import {computed, onBeforeUnmount, onMounted, ref, watch} from "vue";

import iconChevronRight from "@/assets/images/dma/icons/chevron-right.svg";
import CircleDiagram from "@/js/components/dma/CircleDiagram.vue";
import CloseButton from "@/js/components/dma/CloseButton.vue";
import OverlayModal from "@/js/components/dma/OverlayModal.vue";
import TextButton from "@/js/components/dma/TextButton.vue";
import WarningModal from "@/js/components/dma/WarningModal.vue";
import Spinner from "@/js/components/spinner/Spinner.vue";
import {dmaService} from "@/js/service/dmaService";

const props = defineProps({
    domains: {
        type: Array,
        required: true,
    },
    scores: {
        type: Array,
        required: true,
    }
})

const emit = defineEmits(['close']);

const scrollableRef = ref(null);
const circleRef = ref(null);
const domainListRef = ref(null);

const actionPlan = ref(null)
const showErrorModal = ref(false);
const selectedElement = ref(null);

const questionData = ref([]);
const showAdviceElements = ref([]);

onMounted(async () => {
    // get questions for all domains, for cross-domain dependency checks
    for (const domain of props.domains) {
        const result = await dmaService.getQuestions(domain.id);
        questionData.value.push({domain: domain.domain, questions: result.domain_questions});
    }
    // TODO load action plan
    // Initialise an empty action plan
    actionPlan.value = [];
});
onBeforeUnmount(() => {
    // remove scroll listener
    if(scrollableRef.value) {
        scrollableRef.value.removeEventListener("scroll", handleHighlightElement);
    }
})

watch(scrollableRef, async(val) => {
    // when scrollableRef is displayed, attach scroll listener
    if(val) {
        val.addEventListener("scroll", handleHighlightElement);
    }
})

const highlightedScores = computed(() => {
    return props.scores.map(score => ({
        ...score,
        highlighted: selectedElement.value === `${score.domain}|${score.element}`,
        selected: showAdviceElements.value.some(e => e.domain === score.domain && e.element === score.element)
    }));
});

const reportData = computed(() => {
    const data = [];
    for(const domain of props.domains) {
        const elements = getElementResults(domain.domain);
        for(const element of elements) {
            element.indicators = getIndicatorResults(domain.domain, element.element);
            for (const indicator of element.indicators) {
                indicator.advice = getAdviceForIndicator(domain.domain, element.element, indicator.indicator);
            }
        }
        const reportDomain = {
            domain: domain.domain,
            questions: getQuestionsForDomain(domain.domain),
            elements,
        };
        data.push(reportDomain);
    }
    return data;
})

const handleHighlightElement = () => {
    if(window.innerWidth >= 1024) {
        if (scrollableRef.value.scrollTop > 0) {
            const circleRect = circleRef.value.$el.getBoundingClientRect();
            const elementSummaries = document.getElementsByClassName('domain-element');
            let element = null;
            for (let i = 0; i < elementSummaries.length; ++i) {
                if (elementSummaries[i].getBoundingClientRect().top > circleRect.top + (circleRect.height / 2)) {
                    break;
                }
                element = elementSummaries[i];
            }
            if (element) {
                selectedElement.value = element.id.replace('_', ' ');
            }
        } else {
            selectedElement.value = null;
        }
    }
}

const getElementResults = (domainName) => {
    return props.scores.reduce((elements, item) => {
        if(item.domain === domainName) {
            let scoreLabel = "Emerging";
            if (item.score === 2) scoreLabel = 'Developing';
            else if (item.score === 3) scoreLabel = 'Achieving';
            else if (item.score === 4) scoreLabel = 'Excelling';

            const dependency = checkElementDependencies(domainName, item.element);

            elements.push({...item, label: scoreLabel, dependency});
        }
        return elements;
    }, []);
};

const getQuestionsForDomain = (domainName) => {
    const domain = questionData.value.find(d => d.domain === domainName);
    if (!domain) return [];
    return domain.questions;
}

const getIndicatorResults = (domainName, elementName) => {
    const domain = props.domains.find(d=> d.domain === domainName);
    if(domain) {
        return domain.results.filter(result => result.element === elementName);
    }
    return [];
}

const getQuestionForDependency = (dependency) => {
    for (const domain of questionData.value) {
        for (const question of domain.questions) {
            if (question.generated_variable === dependency) {
                return {...question, domain: domain.domain};
            }
        }
    }
    return null;
}

const getQuestionForIndicator = (domainName, elementName, indicatorName, score) => {
    return getQuestionsForDomain(domainName).find(q =>
        q.element_print === elementName &&
        q.indicator_print === indicatorName &&
        q.phase === score);
}

const getAdviceForIndicator = (domainName, elementName, indicatorName) => {
    const domainQuestions = questionData.value.find(d => d.domain === domainName);
    if(!domainQuestions) return null;
    const question = domainQuestions.questions.find(q => q.element_print === elementName && q.indicator_print === indicatorName);
    if (!question) return null;
    // make sure all anchors open in new window
    return question.advice.replace(/<a\s/g,'<a target="_blank" ');
}


const checkIndicatorDependencies = (domainName, elementName, indicatorName, score, prevMetDependencies = []) => {
    const domain = props.domains.find(d=>d.domain === domainName);
    if(!domain) return null;

    // find the question entry for this indicator
    let question = getQuestionForIndicator(domainName, elementName, indicatorName, score);
    if(!question) question = getQuestionForIndicator(domainName, elementName, indicatorName, -1);
    if(!question) return null;

    let dependencies = [];
    // get dependencies (cross-domain only at first level)
    if(question.dependencies) {
        dependencies = question.dependencies.split(' && ').filter(dep => {
            // exclude met dependencies
            if (domain.met_dependencies.includes(dep)) return false;
            // exclude dependencies that would be met by the previous questions
            if (prevMetDependencies.includes(dep)) return false;
            return true;
        });
        for (const dep of dependencies) {
            // get dependency question
            const depQuestion = getQuestionForDependency(dep);
            // check score for dependency question
            const depResult = getIndicatorResults(depQuestion.domain, depQuestion.element_print).find(r => r.indicator === depQuestion.indicator_print);
            if (!depResult || depResult.value < depQuestion.phase) {
                return {domain: depQuestion.domain, element: depQuestion.element_print};
            }
        }
    }
    // if this is not the highest result for this indicator, check if the next higher value has any unmet dependencies
    if (score < 4) {
        return checkIndicatorDependencies(domainName, elementName, indicatorName, score + 1, [...prevMetDependencies, question.generated_variable]);
    }
    return null;
}
const checkElementDependencies = (domainName, elementName) => {
    // check each indicator for this element
    for (const result of getIndicatorResults(domainName, elementName)) {
        const depElement = checkIndicatorDependencies(domainName, result.element, result.indicator, result.value);
        if (depElement) return depElement;
    }
    return null;
}

const handleScrollToElement = async (item) => {
    const elementSummary = document.getElementById(`${item.domain}|${item.element.replace(' ','_')}`);
    if(elementSummary) {
        const scrollPos = scrollableRef.value.scrollTop;
        const circleRect = circleRef.value.$el.getBoundingClientRect();
        const elementRect = elementSummary.getBoundingClientRect();

        if(window.innerWidth < 1024) {
            // mobile view:
            await new Promise(r => setTimeout(r, 500));
            // scroll top of element to below close button, with room for domain header
            scrollableRef.value.scrollTo({top: scrollPos + elementRect.top - 120});
        } else {
            // web view:
            // selection is based on scroll position; scroll top of element to top of circle
            scrollableRef.value.scrollTo({top: scrollPos + elementRect.top - circleRect.top});
        }

    }
}

const showingAdvice = (domainName, elementName) => {
    return showAdviceElements.value.some(e => e.domain === domainName && e.element === elementName);
}

const toggleShowAdvice = async (domainName, elementName) => {
    const index = showAdviceElements.value.findIndex(e => e.domain === domainName && e.element === elementName);
    if(index >= 0) {
        showAdviceElements.value.splice(index, 1);
    } else {
        showAdviceElements.value.push({domain: domainName, element: elementName});
    }
}

const handleErrorDismissed = () => {
    showErrorModal.value = false;
    emit('close');
}

</script>

<template>
    <OverlayModal>
        <div
            class="
                bg-black
                min-h-full
                overflow-hidden
                relative
                text-white
                w-full
                md:!min-h-0
                md:h-full
                md:max-h-[880px]
                md:max-w-[1320px]
                md:rounded-lg
                "
        >
            <CloseButton
                class="absolute top-6 left-4 z-50 md:!left-10 md:!top-10"
                @click="emit('close')"
            />
            <div
                v-if="!actionPlan"
                class="flex justify-center items-center min-h-full w-full md:h-full"
            >
                <Spinner />
            </div>
            <div
                v-else
                ref="scrollableRef"
                class="bg-white h-full overflow-y-scroll scroll-smooth text-black"
            >
                <div
                    class="bg-main-teal/10 max-sm:pt-16 p-5 md:p-20"
                >
                    <div class="flex items-center flex-col text-center">
                        <h1 class="text-h3-caps">
                            <span>Your Digital Capability</span><br>
                            <span class="text-h1-caps">Profile</span>
                        </h1>
                    </div>
                    <div class="max-w-[800px] mx-auto my-10 text-left">
                        <p class="my-2">
                            Congratulations on completing the Digital Capability Leadership Reflection Tool.
                        </p>
                        <p class="my-2">
                            Your school’s personalised Digital Capability Profile has been generated based on your reflections across the
                            {{ scores.length }} elements within the 4 domains of the Digital Capability Framework.
                        </p>

                        <p class="my-2">
                            The school’s current digital capability in each element has been positioned on a continuum ranging from emerging to developing, achieving or excelling.
                        </p>

                        <p class="my-2">
                            To continue on your digital improvement journey, tailored recommendations have been compiled for your identified focus areas.
                        </p>
                    </div>

                    <div class="flex lg:flex-row flex-col gap-10">
                        <div
                            class="
                                flex
                                lg:justify-end
                                flex-col
                                max-lg:mx-auto
                                max-w-[500px]
                                w-full
                                lg:basis-1/3
                                lg:mt-32
                                lg:order-last
                                "
                        >
                            <div class="sticky bottom-20">
                                <div
                                    class="font-bold h-5 max-lg:hidden mb-5 text-center text-sm uppercase"
                                    :class="`text-${selectedElement?.split('|')[0]}`"
                                >
                                    {{ selectedElement?.replace('|',': &nbsp;') }}
                                </div>
                                <CircleDiagram
                                    ref="circleRef"
                                    :scores="highlightedScores"
                                    clickable
                                    @click="handleScrollToElement"
                                />
                                <!--                                <p class="mt-10">-->
                                <!--                                    Please select between 1 and 3 elements that your school would like to focus on-->
                                <!--                                    by checking the boxes next to your chosen elements.-->
                                <!--                                    These will be highlighted on your profile, and your actions plans associated-->
                                <!--                                    with these elements will appear in your printed report.-->
                                <!--                                </p>-->
                            </div>
                        </div>

                        <div
                            ref="domainListRef"
                            class="lg:basis-2/3"
                        >
                            <template
                                v-for="domain of reportData"
                                :key="domain.id"
                            >
                                <div class="mt-10">
                                    <h2
                                        class="text-h2-caps md:text-h3-caps"
                                        :class="`text-${domain.domain}`"
                                    >
                                        {{ domain.domain }} domain
                                    </h2>
                                    <div
                                        v-for="element of domain.elements"
                                        :id="`${domain.domain}|${element.element.replace(' ','_')}`"
                                        :key="element"
                                        class="domain-element mt-5"
                                    >
                                        <h3
                                            class="
                                                bg-black/10
                                                flex
                                                items-center
                                                flex-row
                                                p-2
                                                rounded-lg
                                                text-h4
                                                "
                                            :class="{'bg-black/30': selectedElement === `${domain.domain}|${element.element}`}"
                                        >
                                            <span class="flex-1 mr-2">{{ element.element }} is {{ element.label }}</span>
                                            <TextButton
                                                class="!text-xs underline"
                                                @click="() => toggleShowAdvice(element.domain, element.element)"
                                            >
                                                <span class="hidden md:block">{{ showingAdvice(element.domain, element.element) ? 'Hide' : 'Show' }} advice & action plan</span>
                                                <span class="md:hidden">advice & plan</span>
                                            </TextButton>
                                            <img
                                                :src="iconChevronRight"
                                                class="brightness-0 scale-75"
                                                :class="{'rotate-90': showingAdvice(element.domain, element.element)}"
                                            >
                                        </h3>

                                        <button
                                            v-if="element.dependency && element.dependency.element !== element.element"
                                            class="bg-red-500/20 mt-5 p-2 rounded w-full"
                                            @click="() => handleScrollToElement(element.dependency)"
                                        >
                                            It is recommended you focus on <b
                                                class="capitalize"
                                            >
                                                {{ element.dependency.domain }}: {{ element.dependency.element }}
                                            </b><br>before advancing this element.
                                        </button>

                                        <div
                                            v-for="indicator of getIndicatorResults(domain.domain, element.element)"
                                            :key="`${indicator.element}_${indicator.indicator}`"
                                            class="mt-5"
                                        >
                                            <p v-html="indicator.description" />

                                            <div
                                                v-if="showingAdvice(element.domain, element.element)"
                                                class="advice my-4 p-4 rounded-lg text-white"
                                                :class="`advice-bg-${domain.domain}`"
                                            >
                                                <p class="font-bold mb-4">
                                                    Suggested strategies for further school development include:
                                                </p>
                                                <p v-html="indicator.advice" />
                                            </div>
                                        </div>

                                        <textarea
                                            v-if="showingAdvice(element.domain, element.element)"
                                            rows="7"
                                            :disabled="props.disabled"
                                            class="
                                                bg-black/10
                                                border-none
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
                                            placeholder="After reviewing the advice offered, explain how you intend to advance towards the next phase for this element of the framework."
                                        />
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <WarningModal
                v-if="showErrorModal"
                embed
                :show-cancel="false"
                @reset="handleErrorDismissed"
            >
                <template #title>
                    Network error
                </template>
                <template #message>
                    A network error has occured.<br>
                    Your progress to this point has been saved.<br>
                    <br>
                    Please wait a moment and try again.
                </template>
            </WarningModal>
        </div>
    </OverlayModal>
</template>

<style scoped lang="scss">
.advice {
    :deep(a) {
        text-decoration: underline;
    }
    :deep(ul) {
        list-style: revert;
        padding-left: 1em;
    }
}
</style>
