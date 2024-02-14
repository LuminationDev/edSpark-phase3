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

// TODO survey details populated by API
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
    <div id="dma-app">
        <BaseLandingHero
            :title="LandingHeroText['dma']['title']"
            :title-paragraph="LandingHeroText['dma']['subtitle']"
            swoosh-color="teal"
        >
            <template #robotIllustration>
                <InspirationAndGuidesRobot class="absolute top-10 left-36" />
            </template>
        </BaseLandingHero>
        <BaseLandingSection
            background-color="white"
        >
            <template #title>
                Your DMA
            </template>
            <template #content>
                <div v-if="!surveyDetails">
                    <!-- TODO display loading spinner, handle API error-->
                    Loading...
                </div>
                <div
                    v-else
                    class="DMAColContainer grid grid-cols-1 gap-10 mt-10 md:!grid-cols-2"
                >
                    <div
                        class="DMADomainContainer flex-1 grid grid-cols-1 gap-10"
                    >
                        <BaseLandingSection>
                            <template #title>
                                Results
                            </template>
                            <template #subtitle>
                                After completing your evaluation, a chart will be updated with your performance below.
                            </template>
                            <template #content>
                                <CircleDiagram :scores="categoryScores" />
                            </template>
                        </BaseLandingSection>
                        <p>
                            <!-- TODO correct this information -->
                            The tool is for you and your school. Your data is only stored locally in the
                            current profile of your web browser. You can generate a PDF report for sharing within
                            your school or including in your next round of School Improvement planning.
                            <button
                                v-if="isInProgress && !isDomainResetting()"
                                class="block mt-10 underline"
                                @click="showResetModal = true"
                            >
                                Reset progress
                            </button>
                        </p>
                    </div>

                    <div
                        class="DMADomainContainer grid grid-cols-1 gap-10"
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
                Frequently Asked
            </template>
            <template #subtitle>
                <div class="mb-10">
                    The Digital Adoption Group (DAG) offers comprehensive guidance on digital technologies,
                    providing practical, system-wide advice for purchasing and adopting high-impact technologies
                    that enhance teaching and learning.
                </div>
            </template>
            <template #content>
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
            </template>
        </BaseLandingSection>
    </div>

    <SurveyModal
        v-if="showSurveyModal && surveyDetails && selectedDomainId"
        :survey="surveyDetails"
        :domain-id="selectedDomainId"
        @close="handleCloseSurveyModal"
        @reset="handleResetDomain"
    />
</template>
