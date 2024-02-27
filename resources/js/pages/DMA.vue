<script setup>
import "@/css/dma/index.css";

import {computed, onMounted, ref} from 'vue';

import BaseLandingHero from "@/js/components/bases/BaseLandingHero.vue";
import BaseLandingSection from "@/js/components/bases/BaseLandingSection.vue";
import CircleDiagram from "@/js/components/dma/CircleDiagram.vue";
import DomainSummary from "@/js/components/dma/DomainSummary.vue";
import FaqEntry from "@/js/components/dma/FaqEntry.vue";
import ReportModal from "@/js/components/dma/ReportModal.vue";
import RoundButton from "@/js/components/dma/RoundButton.vue";
import SurveyModal from "@/js/components/dma/SurveyModal.vue";
import WarningModal from "@/js/components/dma/WarningModal.vue";
import InspirationAndGuidesRobot from "@/js/components/inspirationandguides/InspirationAndGuidesRobot.vue";
import {LandingHeroText} from "@/js/constants/PageBlurb";
import {dmaService} from "@/js/service/dmaService";

const showSurveyModal = ref(false);
const showReportModal = ref(false);

const surveyDetails = ref(null);

const selectedDomainId = ref(null);
const resetting = ref(false);

const showResetModal = ref(false);
const showErrorModal = ref(false);
const showSurveyError = ref(false);

onMounted(async () => {
    await fetchUserSurvey();
})

const domains = computed(() => {
    const list = [];
    if (surveyDetails.value) {
        // Return domains in a predefined order, excluding triage
        // If any domains are not found in surveyDetails, they will be skipped
        const teaching = surveyDetails.value.survey_domains.find(d => d.domain === 'teaching');
        if (teaching) list.push(teaching);
        const learning = surveyDetails.value.survey_domains.find(d => d.domain === 'learning');
        if (learning) list.push(learning);
        const leading = surveyDetails.value.survey_domains.find(d => d.domain === 'leading');
        if (leading) list.push(leading);
        const managing = surveyDetails.value.survey_domains.find(d => d.domain === 'managing');
        if (managing) list.push(managing);
    }
    return list;
});

const isInProgress = computed(() => {
    if (surveyDetails.value.survey_domains.length) {
        return surveyDetails.value.survey_domains.some(d => d.completed_question_count > 0);
    }
})

const isCompleted = computed(() => {
    if (surveyDetails.value.survey_domains.length) {
        return surveyDetails.value.survey_domains.every(d => d.completed_question_count === d.question_count);
    }
})

const indicatorScores = computed(() => {
    const scores = [];
    if (domains.value) {
        for (const domain of domains.value) {
            const elements = [];
            for (const result of domain.results) {
                let element = elements.find(c => c.name === result.element);
                if (!element) {
                    element = { name: result.element, scores: [], completed: false};
                    elements.push(element);
                }
                if (elements.length <= domain.completed_element_count) {
                    element.completed = true;
                }
                element.scores.push(result.value);
            }
            for (const element of elements) {
                // if no questions have been answered, score is 0;
                // otherwise, it is the average of all indicator scores
                let score = 0;
                if(element.completed) {
                    score = Math.round(element.scores.reduce((sum, val) => {
                        return sum + (val || 1); // treat 0 as 1
                    }, 0) / element.scores.length);
                }
                scores.push({
                    element: element.name,
                    domain: domain.domain,
                    score,
                });
            }
        }
    }
    return scores;
})

const fetchUserSurvey = async () => {
    showSurveyError.value = false;
    dmaService.getSurvey().then((result) => {
        surveyDetails.value = result;
    }).catch((error) => {
        console.log("API error getting survey:", error);
        showSurveyError.value = true;
    })
}

const handleLaunchSurvey = async (domainId) => {
    // if triage is started but incomplete, reset the triage domain,
    // then refetch the survey as the triage ID will have changed
    const triage = surveyDetails.value.survey_domains.find(d => d.domain === 'triage');
    if (triage.completed_question_count > 0 && triage.completed_question_count < triage.question_count) {
        dmaService.resetDomainProgress(triage.id).then((result) => {
            surveyDetails.value = result;
            fetchUserSurvey();
        }).catch((error) => {
            console.log("API error resetting triage:", error);
            showErrorModal.value = true;
        });
    }

    selectedDomainId.value = domainId;
    showSurveyModal.value = true;
}

const handleCloseSurveyModal = () => {
    showSurveyModal.value = false;
    selectedDomainId.value = null;
    fetchUserSurvey();
}

const handleCloseReportModal = () => {
    showReportModal.value = false;
}

const showResetting = async () => {
    resetting.value = true;
    await new Promise(r => setTimeout(r, 4000));
    await fetchUserSurvey();
    resetting.value = false
}

const handleResetDomain = async () => {
    showSurveyModal.value = false;
    dmaService.resetDomainProgress(selectedDomainId.value).then(() => {
        showResetting();
    }).catch((error) => {
        console.log("API error resetting domain:", error);
        showErrorModal.value = true;
    })

}

const isDomainResetting = (domainId = null) => {
    return resetting.value && (!selectedDomainId.value || selectedDomainId.value === domainId);
}

const handleResetSurvey = async () => {
    showResetModal.value = false;
    dmaService.resetSurveyProgress().then(() => {
        showResetting();
    }).catch((error) => {
        console.log("API error resetting survey:", error);
        showErrorModal.value = true;
    })
}

</script>

<template>
    <div>
        <BaseLandingHero
            :title="LandingHeroText['dma']['title']"
            :title-paragraph="LandingHeroText['dma']['subtitle']"
            swoosh-color="teal"
        >
            <template #robotIllustration>
                <InspirationAndGuidesRobot class="absolute top-10 left-36" />
            </template>
        </BaseLandingHero>
        <div class="dma-app-root">
            <BaseLandingSection
                background-color="white"
            >
                <template #title>
                    <h2 class="px-4 text-h2 md:text-h3 md:!px-5 lg:!px-0">
                        Your DMA
                    </h2>
                </template>
                <template #content>
                    <div
                        v-if="showSurveyError"
                        class="font-bold my-2"
                    >
                        There was a network error obtaining your latest assessment data. <button
                            class="underline"
                            @click="fetchUserSurvey"
                        >
                            Refresh survey
                        </button>
                    </div>
                    <div v-else-if="!surveyDetails">
                        <!-- TODO display loading spinner -->
                        Loading...
                    </div>
                    <div
                        v-else
                        class="DMAColContainer grid grid-cols-1 gap-10 mt-10 xl:!grid-cols-2"
                    >
                        <div
                            class="DMADomainContainer flex sm:flex-1/3 xl:flex-1 flex-col gap-10"
                        >
                            <div
                                class="
                                    bg-main-teal/10
                                    flex
                                    sm:flex-row
                                    flex-col
                                    px-4
                                    py-10
                                    gap-10
                                    md:!px-10
                                    xl:!flex-col
                                    xl:!gap-14
                                    "
                            >
                                <div class="flex flex-1 flex-col gap-4 md:!basis-2/5">
                                    <h3 class="text-h2 md:text-h3">
                                        Results
                                    </h3>
                                    <p
                                        v-if="isCompleted"
                                        class="text-base"
                                    >
                                        This chart shows your performance in each of the assessed elements.
                                    </p>
                                    <p
                                        v-else
                                        class="text-base"
                                    >
                                        After completing your evaluation, this
                                        chart will be updated with your
                                        performance.
                                    </p>
                                </div>
                                <div
                                    class="flex flex-1 flex-col gap-5 md:!basis-3/5"
                                >
                                    <CircleDiagram :scores="indicatorScores" />
                                    <RoundButton
                                        v-if="isCompleted"
                                        @click="showReportModal = true"
                                    >
                                        View assessment report
                                    </RoundButton>
                                </div>
                            </div>
                            <p class="px-4 text-base md:!px-5 lg:!px-0">
                                <!-- TODO correct this information -->
                                The tool is for you and your school. Your data is only stored locally in the
                                current profile of your web browser. You can generate a PDF report for sharing within
                                your school or including in your next
                                round of School Improvement planning.

                                <span class="block h-6 mt-7">
                                    <button
                                        v-if="isInProgress && !isDomainResetting()"
                                        class="block font-semibold underline"
                                        @click="showResetModal = true"
                                    >
                                        Reset progress
                                    </button>
                                </span>
                            </p>
                        </div>

                        <div
                            class="DMADomainContainer flex flex-col gap-5 px-4 md:!gap-6 md:!px-5 lg:!gap-8 lg:!px-0"
                        >
                            <DomainSummary
                                v-for="domain of domains"
                                :key="domain.id"
                                :domain="domain"
                                :resetting="isDomainResetting(domain.id)"
                                @click="handleLaunchSurvey(domain.id)"
                            />
                        </div>
                    </div>
                    <WarningModal
                        v-if="showResetModal"
                        @cancel="showResetModal=false"
                        @reset="handleResetSurvey"
                    >
                        <template #title>
                            Are you sure?
                        </template>
                        <template #message>
                            Resetting will erase all your progress on the assessment.
                        </template>
                        <template #confirm>
                            Reset
                        </template>
                    </WarningModal>
                </template>
            </BaseLandingSection>

            <BaseLandingSection>
                <template #title>
                    <h2 class="px-4 text-h3 md:!px-5 lg:!px-0">
                        Frequently Asked
                    </h2>
                </template>
                <template #subtitle>
                    <div class="mb-10 px-4 text-medium md:!px-5 lg:!px-0">
                        The Digital Adoption Group (DAG) offers comprehensive guidance on digital technologies,
                        providing practical, system-wide advice for purchasing and adopting high-impact technologies
                        that enhance teaching and learning.
                    </div>
                </template>
                <template #content>
                    <div class="flex flex-col gap-5 lg:gap-7 px-4 md:!px-5 lg:!px-0">
                        <FaqEntry>
                            <template #question>
                                Can I pause anytime?
                            </template>
                            <template #answer>
                                <!-- TODO add answer -->
                                Yes, your progress is recorded as you go, and you can resume where you left off.
                            </template>
                        </FaqEntry>
                        <FaqEntry>
                            <template #question>
                                How do I reset my progress?
                            </template>
                            <template #answer>
                                <!-- TODO add answer -->
                                When viewing any domain, you can click 'Reset progress'
                                and all answers for that domain will be cleared.
                                <br>
                                To reset the entire assessment, click 'Reset progress' below the performance chart.
                            </template>
                        </FaqEntry>
                        <FaqEntry>
                            <template #question>
                                Where is my data stored?
                            </template>
                            <template #answer>
                                <!-- TODO add answer -->
                                ...
                            </template>
                        </FaqEntry>
                    </div>
                </template>
            </BaseLandingSection>
        </div>
    </div>

    <SurveyModal
        v-if="showSurveyModal && surveyDetails && selectedDomainId"
        :survey="surveyDetails"
        :domain-id="selectedDomainId"
        @close="handleCloseSurveyModal"
        @reset="handleResetDomain"
    />

    <ReportModal
        v-if="showReportModal && surveyDetails"
        :domains="domains"
        :scores="indicatorScores"
        @close="handleCloseReportModal"
    />

    <WarningModal
        v-if="showErrorModal"
        :show-cancel="false"
        @reset="showErrorModal = false"
    >
        <template #title>
            Network error
        </template>
        <template #message>
            A network error has occurred while resetting your survey.<br>
            <br>
            Please wait a moment before trying again.
        </template>
    </WarningModal>
</template>
