<script setup>
import "@/css/dma/index.css";

import {computed, onMounted, ref} from 'vue';

import BaseLandingHero from "@/js/components/bases/BaseLandingHero.vue";
import BaseLandingSection from "@/js/components/bases/BaseLandingSection.vue";
import CircleDiagram from "@/js/components/dma/CircleDiagram.vue";
import DomainSummary from "@/js/components/dma/DomainSummary.vue";
import FaqEntry from "@/js/components/dma/FaqEntry.vue";
import SurveyModal from "@/js/components/dma/SurveyModal.vue";
import WarningModal from "@/js/components/dma/WarningModal.vue";
import InspirationAndGuidesRobot from "@/js/components/inspirationandguides/InspirationAndGuidesRobot.vue";
import {LandingHeroText} from "@/js/constants/PageBlurb";
import {dmaService} from "@/js/service/dmaService";

const showSurveyModal = ref(false);

const surveyDetails = ref(null);

const selectedDomainId = ref(null);
const resetting = ref(false);

const showResetModal = ref(false);

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

const categoryScores = computed(() => {
    const scores = [];
    if (domains.value) {
        for (const domain of domains.value) {
            for (const result of domain.results) {
                scores.push({
                    category: result.category,
                    domain: domain.domain,
                    score: result.value,
                })
            }
        }
    }
    return scores;
})

const fetchUserSurvey = async () => {
    surveyDetails.value = await dmaService.getSurvey();
}

const handleLaunchSurvey = async (domainId) => {
    // if triage is started but incomplete, reset the triage domain,
    // then refetch the survey as the triage ID will have changed
    const triage = surveyDetails.value.survey_domains.find(d => d.domain === 'triage');
    if (triage.completed_question_count > 0 && triage.completed_question_count < triage.question_count) {
        await dmaService.resetDomainProgress(triage.id);
        await fetchUserSurvey();
    }

    selectedDomainId.value = domainId;
    showSurveyModal.value = true;
}

const handleCloseSurveyModal = () => {
    showSurveyModal.value = false;
    selectedDomainId.value = null;
    fetchUserSurvey();
}

const showResetting = async () => {
    resetting.value = true;
    await new Promise(r => setTimeout(r, 4000));
    await fetchUserSurvey();
    resetting.value = false
}

const handleResetDomain = async () => {
    showSurveyModal.value = false;
    await dmaService.resetDomainProgress(selectedDomainId.value);
    await showResetting();
}

const isDomainResetting = (domainId = null) => {
    return resetting.value && (!selectedDomainId.value || selectedDomainId.value === domainId);
}

const handleResetSurvey = async () => {
    showResetModal.value = false;
    await dmaService.resetSurveyProgress();
    await showResetting();

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
                    <h2 class="px-3 text-h2 md:text-h3 md:!px-5 lg:!px-0">
                        Your DMA
                    </h2>
                </template>
                <template #content>
                    <div v-if="!surveyDetails">
                        <!-- TODO display loading spinner, handle API error-->
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
                                    px-3
                                    py-10
                                    gap-10
                                    md:!px-10
                                    md:!py-14
                                    lg:!p-14
                                    xl:!flex-col
                                    xl:!gap-14
                                    xl:p-10
                                    "
                            >
                                <div class="flex flex-1 flex-col gap-4 md:!basis-2/5">
                                    <h3 class="text-h2 md:text-h3">
                                        Results
                                    </h3>
                                    <p class="text-base">
                                        After completing your evaluation, this
                                        chart will be updated with your
                                        performance.
                                    </p>
                                </div>
                                <div
                                    class="flex-1 md:!basis-3/5"
                                >
                                    <CircleDiagram :scores="categoryScores" />
                                </div>
                            </div>
                            <p class="px-3 text-base md:!px-5 lg:!px-0">
                                <!-- TODO correct this information -->
                                The tool is for you and your school. Your data is only stored locally in the
                                current profile of your web browser. You can generate a PDF report for sharing within
                                your school or including in your next round of School Improvement planning.
                                <button
                                    v-if="isInProgress && !isDomainResetting()"
                                    class="block font-semibold mt-10 underline"
                                    @click="showResetModal = true"
                                >
                                    Reset progress
                                </button>
                            </p>
                        </div>

                        <div
                            class="DMADomainContainer flex flex-col gap-5 px-3 md:!gap-7 md:!px-5 lg:!gap-9 lg:!px-0"
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
                            Resetting will erase all your progress on the survey.
                        </template>
                    </WarningModal>
                </template>
            </BaseLandingSection>

            <BaseLandingSection>
                <template #title>
                    <h2 class="px-3 text-h3 md:!px-5 lg:!px-0">
                        Frequently Asked
                    </h2>
                </template>
                <template #subtitle>
                    <div class="mb-10 px-3 text-medium md:!px-5 lg:!px-0">
                        The Digital Adoption Group (DAG) offers comprehensive guidance on digital technologies,
                        providing practical, system-wide advice for purchasing and adopting high-impact technologies
                        that enhance teaching and learning.
                    </div>
                </template>
                <template #content>
                    <div class="flex flex-col gap-5 lg:gap-7 px-3 md:!px-5 lg:!px-0">
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
                                To reset the entire survey, click 'Reset progress' below the performance chart.
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
</template>
