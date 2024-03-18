<script setup>
import {computed, onBeforeUnmount, onMounted, ref, watch} from "vue";

import iconChevronRight from "@/assets/images/dma/icons/chevron-right.svg";
import CircleDiagram from "@/js/components/dma/CircleDiagram.vue";
import CloseButton from "@/js/components/dma/CloseButton.vue";
import OverlayModal from "@/js/components/dma/OverlayModal.vue";
import RoundButton from "@/js/components/dma/RoundButton.vue";
import TextButton from "@/js/components/dma/TextButton.vue";
import WarningModal from "@/js/components/dma/WarningModal.vue";
import Spinner from "@/js/components/spinner/Spinner.vue";
import {dmaService} from "@/js/service/dmaService";


import PDFBuilder from "@/js/components/global/PDFBuilder.vue";


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

const actionPlanData = ref(null)
const actionPlan = ref(null)
const showUnsavedWarningModal = ref(false);
const selectedElement = ref(null);

const questionData = ref([]);
const elementData = ref([]);

const showErrorModal = ref(false);
const closeOnError = ref(false);

onMounted(async () => {
    // get questions for all domains, for cross-domain dependency checks
    const data = [];
    const data_desc = [];
    try {
        for (const domain of props.domains) {
            const result = await dmaService.getQuestions(domain.id);            
            data.push({domain: domain.domain, domainId: domain.id, questions: result.domain_questions});

            const result_desc = await dmaService.getElementDescriptions(domain.id);
            data_desc.push({ domain: domain.id, domain_label: domain.domain, elements: result_desc });
        }
        questionData.value = data;
        elementData.value = data_desc;

        // load action plan
        actionPlanData.value = await dmaService.getActionPlans();
    } catch(error) {
        console.log("Error loading assessment report data", error);
        closeOnError.value = true;
        showErrorModal.value = true
    }
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
    return props.scores.map(score => {
        return {
            ...score,
            highlighted: selectedElement.value === `${score.domain}|${score.element}`,
            selected: actionPlan.value ? actionPlan.value[score.domain][score.element].expanded : false,
        }
    });
});

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
            const test = 'test';


            elements.push({...item, label: scoreLabel, dependency, test});
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
            if (depQuestion) {
                const depResult = getIndicatorResults(depQuestion.domain, depQuestion.element_print).find(r => r.indicator === depQuestion.indicator_print);
                console.log("DEPRES", depResult);
                if (!depResult || depResult.value < depQuestion.phase) {
                    return {domain: depQuestion.domain, element: depQuestion.element_print};
                }
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
            id: domain.id,
            domain: domain.domain,
            questions: getQuestionsForDomain(domain.domain),
            elements,
        };
        data.push(reportDomain);
    }
    return data;
})

watch([reportData, actionPlanData], async([data, planData]) => {
    if(data && planData) {
        // initialise actionPlan with data from report
        const plan = {};
        for (const domain of data) {
            plan[domain.domain] = {};
            for (const element of domain.elements) {
                const currentPlan = planData.action_plan[domain.domain]?.find(e => e.element === element.element);
                if (currentPlan) {
                    // map loaded action plan
                    plan[domain.domain][element.element] = { expanded: !!currentPlan.action, action_plan: currentPlan.action, edited: false};
                } else {
                    plan[domain.domain][element.element] = {expanded: false, action_plan: null, edited: false};
                }
            }
        }
        actionPlan.value = plan;
    }
});

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

const handleSaveActionPlan = async (domain, elementName) => {
    const plan = actionPlan.value[domain.domain][elementName];
    try {
        if (plan.action_plan) {
            await dmaService.putActionPlan(domain.id, elementName, plan.action_plan);
        } else {
            await dmaService.deleteActionPlan(domain.id, elementName);
        }
        actionPlan.value[domain.domain][elementName].edited = false;
    } catch(error) {
        console.log("error saving action plan", error);
        showErrorModal.value = true;
    }
}

const toggleShowAdvice = async (domainName, elementName) => {
    const el = actionPlan.value[domainName][elementName];
    el.expanded = !el.expanded;
}

const handleErrorDismissed = () => {
    showErrorModal.value = false;
    if(closeOnError.value) {
        emit('close');
    }
}

const handleCloseReport = () => {
    if (actionPlan.value) {
        // check for unsaved changes
        for (const domainData of Object.values(actionPlan.value)) {
            for (const plan of Object.values(domainData)) {
                if (plan.edited) {
                    showUnsavedWarningModal.value = true;
                    return;
                }
            }
        }
    }
    emit('close');
}

</script>

<template>
    <OverlayModal>
        <div
            class="
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
            :class="actionPlan ? 'bg-white' : 'bg-black'"
        >
            <CloseButton
                class="absolute top-6 left-4 z-50 md:!left-10 md:!top-10"
                @click="handleCloseReport"
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
                class="bg-main-teal/5 h-full overflow-y-scroll scroll-smooth text-black"
            >
                <div
                    class="max-sm:pt-16 p-5 md:p-20"
                >
                    <div class="flex items-center flex-col text-center">
                        <h1 class="text-h3-caps">
                            <span>Your Digital Capability</span><br>
                            <span class="text-h1-caps uppercase">Profile</span>
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

                    <div class="max-w-[800px] mx-auto my-10 text-left">
                        <p>
                            Please select between 1 and 3 elements that your school would like to focus on
                            by clicking 'Show advice & action plan' and filling in your action plan in the box provided.
                            These elements will be highlighted on your profile.
                        </p>
                    </div>

                    <PDFBuilder
                        :domains="domains"
                        :questionData="questionData"
                        :reportData="reportData"
                        :elementData="elementData"
                        />


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
                                                bg-secondary-coolGrey
                                                flex
                                                items-center
                                                flex-row
                                                p-2
                                                pl-4
                                                rounded
                                                text-h4
                                                bg-secondary-coolGrey 
                                                mt-10 mb-6
                                                "
                                            :class="{'brightness-75': selectedElement === `${domain.domain}|${element.element}`}"
                                        >
                                            <span class="flex-1 mr-2 text-h4-caps">{{ element.element }} is {{ element.label }}</span>
                                            <TextButton
                                                class="!text-xs underline"
                                                @click="() => toggleShowAdvice(element.domain, element.element)"
                                            >
                                                <span class="hidden md:block !normal-case text-sm">{{ actionPlan[domain.domain][element.element].expanded ? 'Hide' : 'Show' }} advice & action plan</span>
                                                <span class="md:hidden">advice & plan</span>
                                            </TextButton>
                                            <img
                                                :src="iconChevronRight"
                                                class="brightness-0 scale-75"
                                                :class="{'rotate-90': actionPlan[domain.domain][element.element].expanded}"
                                            >
                                        </h3>

                                        <button
                                            v-if="element.dependency && element.dependency.element !== element.element"
                                            class="
                                                bg-red-500/20
                                                hover:bg-red-500/30
                                                mt-5
                                                p-2
                                                rounded-lg
                                                w-full
                                                "
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
                                                v-if="actionPlan[domain.domain][element.element].expanded"
                                                class="advice my-4 p-4 rounded-lg text-white"
                                                :class="`advice-bg-${domain.domain}`"
                                            >
                                                <p class="font-bold mb-4">
                                                    Suggested strategies for further school development include:
                                                </p>
                                                <p v-html="indicator.advice" />
                                            </div>
                                        </div>
                                        <div v-if="actionPlan[domain.domain][element.element].expanded">
                                            <textarea
                                                v-model="actionPlan[domain.domain][element.element].action_plan"
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
                                                @keyup="actionPlan[domain.domain][element.element].edited = true"
                                            />
                                            <div class="flex justify-end gap-5 mt-2">
                                                <RoundButton
                                                    :disabled="!actionPlan[domain.domain][element.element].edited"
                                                    @click="handleSaveActionPlan(domain,element.element)"
                                                >
                                                    Save
                                                </RoundButton>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="mt-10 text-center">
                        <RoundButton                         
                        color="bg-white"
                        text-color="black"
                        class="hover:!bg-secondary-coolGrey hover:!brightness-100"
                        @click="handleCloseReport">
                            Close
                        </RoundButton>
                    </div>
                </div>

                <WarningModal
                    v-if="showUnsavedWarningModal"
                    embed
                    @cancel="showUnsavedWarningModal = false"
                    @confirm="emit('close')"
                >
                    <template #title>
                        Unsaved changes
                    </template>
                    <template #message>
                        You have unsaved changes to your action plans that will not be saved. Continue?
                    </template>
                </WarningModal>
            </div>
            <WarningModal
                v-if="showErrorModal"
                embed
                :show-cancel="false"
                @confirm="handleErrorDismissed"
            >
                <template #title>
                    Network error
                </template>
                <template #message>
                    A network error has occured.<br>
                    <br>
                    Please wait a moment and try again.
                </template>
            </WarningModal>
        </div>
    </OverlayModal>
</template>

<style scoped lang="scss">
::-webkit-scrollbar-track {
    background-color: transparent;
}
.advice {
    overflow-wrap: anywhere;
    :deep(a) {
        text-decoration: underline;
    }
    :deep(ul) {
        list-style: revert;
        padding-left: 1em;
    }
}
</style>
