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

watch(scrollableRef, async(newVal, oldVal) => {
    // when scrollableRef is displayed, attach scroll listener
    if(newVal) {
        newVal.addEventListener("scroll", handleHighlightElement);
        handleHighlightElement();
    }
})

const highlightedScores = computed(() => {
    return props.scores.map(score => ({
        ...score, selected: selectedElement.value === `${score.domain}|${score.element}`
    }));
});

const handleHighlightElement = () => {
    const circleRect = circleRef.value.$el.getBoundingClientRect();
    const elementSummaries = document.getElementsByClassName('domain-element');
    let element = null;
    for (let i = 0; i < elementSummaries.length; ++i) {
        if (elementSummaries[i].getBoundingClientRect().top > circleRect.top + (circleRect.height/2)) {
            break;
        }
        element = elementSummaries[i];
    }
    selectedElement.value = element.id.replace('_',' ');
}

const getElements = (domainName) => {
    return props.scores.reduce((elements, item) => {
        if(item.domain === domainName) {
            let scoreLabel = "Emerging";
            if (item.score === 2) scoreLabel = 'Developing';
            else if (item.score === 3) scoreLabel = 'Achieving';
            else if (item.score === 4) scoreLabel = 'Excelling';

            elements.push({...item, label: scoreLabel});
        }
        return elements;
    }, []);
};

const getIndicatorResults = (domainName, elementName) => {
    const domain = props.domains.find(d=> d.domain === domainName);
    if(domain) {
        return domain.results.filter(result => result.element === elementName);
    }
    return [];
}

const handleDiagramClick = (item) => {
    const elementSummary = document.getElementById(`${item.domain}|${item.element.replace(' ','_')}`);
    if(elementSummary) {
        const scrollPos = scrollableRef.value.scrollTop;
        const circleRect = circleRef.value.$el.getBoundingClientRect();
        const elementRect = elementSummary.getBoundingClientRect();

        scrollableRef.value.scrollTo({top: scrollPos + elementRect.top - circleRect.top});

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
        // fetch question data for this domain if it is not available
        if(!questionData.value.some(q => q.domain === domainName)) {
            const domain = props.domains.find(d => d.domain === domainName);
            if (!domain) return;
            const result = await dmaService.getQuestions(domain.id);
            if (!result) return;
            questionData.value.push({domain: domainName, questions: result.domain_questions});
        }
        showAdviceElements.value.push({domain: domainName, element: elementName});
    }
}

const getAdviceForIndicator = (domainName, elementName, indicatorName) => {
    const domainQuestions = questionData.value.find(d => d.domain === domainName);
    if(!domainQuestions) return null;
    const question = domainQuestions.questions.find(q => q.element_print === elementName && q.indicator_print === indicatorName);
    if (!question) return null;
    // make sure all anchors open in new window
    return question.advice.replace('<a ','<a target="_blank" ');
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
                    class="bg-main-teal/10 p-20"
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

                    <div class="flex md:flex-row flex-col gap-10">
                        <div class="flex md:justify-end flex-col max-w-[600px] mt-32 w-full md:basis-1/3 md:order-last">
                            <div class="sticky bottom-20">
                                <div
                                    class="font-bold mb-5 text-center text-sm uppercase"
                                    :class="`text-${selectedElement?.split('|')[0]}`"
                                >
                                    {{ selectedElement?.replace('|',': &nbsp;') }}
                                </div>
                                <CircleDiagram
                                    ref="circleRef"
                                    :scores="highlightedScores"
                                    clickable
                                    @click="handleDiagramClick"
                                />
                            <!--                        <p class="mt-10">-->
                            <!--                            Please select between 1 and 3 elements that your school would like to focus on-->
                            <!--                            by checking the boxes next to your chosen elements.-->
                            <!--                            These will be highlighted on your profile, and your actions plans associated-->
                            <!--                            with these elements will appear in your printed report.-->
                            <!--                        </p>-->
                            </div>
                        </div>

                        <div
                            ref="domainListRef"
                            class="md:basis-2/3"
                        >
                            <template
                                v-for="domain of domains"
                                :key="domain.id"
                            >
                                <div class="mt-10">
                                    <h2
                                        class="text-h2-caps"
                                        :class="`text-${domain.domain}`"
                                    >
                                        {{ domain.domain }} domain
                                    </h2>
                                    <div
                                        v-for="element of getElements(domain.domain)"
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
                                            <span class="flex-1">{{ element.element }} is {{ element.label }}</span>
                                            <TextButton
                                                class="!text-xs underline"
                                                @click="() => toggleShowAdvice(element.domain, element.element)"
                                            >
                                                {{ showingAdvice(element.domain, element.element) ? 'Hide' : 'Show' }} advice & action plan
                                            </TextButton>
                                            <img
                                                :src="iconChevronRight"
                                                class="brightness-0 scale-75"
                                                :class="{'rotate-90': showingAdvice(element.domain, element.element)}"
                                            >
                                        </h3>

                                        <div
                                            v-for="result of getIndicatorResults(domain.domain, element.element)"
                                            :key="`${result.element}_${result.indicator}`"
                                            class="mt-5"
                                        >
                                            <p v-html="result.description" />

                                            <div
                                                v-if="showingAdvice(element.domain, element.element)"
                                                class="advice my-4 p-4 rounded-lg text-white"
                                                :class="`advice-bg-${domain.domain}`"
                                            >
                                                <p class="font-bold mb-4">
                                                    Suggested strategies for further school development include:
                                                </p>
                                                <p v-html="getAdviceForIndicator(element.domain, element.element, result.indicator)" />
                                            </div>
                                        </div>
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
@media screen and (min-width: 768px) {
  .scroll-fade {
    mask-image: linear-gradient(transparent, black 5%, black 95%, transparent);
  }
}
</style>
