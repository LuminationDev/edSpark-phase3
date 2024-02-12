<script setup>
import {computed, onMounted, ref} from 'vue';

import BaseLandingHero from "@/js/components/bases/BaseLandingHero.vue";
import BaseLandingSection from "@/js/components/bases/BaseLandingSection.vue";
import DomainSummary from "@/js/components/dma/DomainSummary.vue";
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
    if (!surveyDetails.value) return [];
    return surveyDetails.value.survey_domains.filter(d => d.domain !== 'triage');
});

const isInProgress = computed(() => {
    if (domains.value.length) {
        return domains.value.some(d => d.completed_question_count > 0);
    }
})

const fetchUserSurvey = async () => {
    surveyDetails.value = await dmaService.getSurvey();
}

const handleLaunchSurvey = (domainId) => {
    selectedDomainId.value = domainId;
    showSurveyModal.value = true;
}

const handleCloseSurveyModal = () => {
    showSurveyModal.value = false;
    // refetch survey data
    fetchUserSurvey();
}

const showResetting = () => {
    resetting.value = true;
    setTimeout(() => {
        resetting.value = false
        // refetch survey data
        fetchUserSurvey();
    }, 4000);
}

const handleResetDomain = () => {
    showSurveyModal.value = false;
    // TODO perform reset on domain
    showResetting();
}

const isDomainResetting = (domainId) => {
    return resetting.value && (!selectedDomainId.value || selectedDomainId.value === domainId);
}

const handleResetSurvey = () => {
    showResetModal.value = false;
    // TODO perform reset on survey
    showResetting();

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
                                (circle diagram)
                            </template>
                        </BaseLandingSection>
                        <p>
                            The tool is for you and your school. Your data is only stored locally in the
                            current profile of your web browser. You can generate a PDF report for sharing within
                            your school or including in your next round of School Improvement planning.
                            <button
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
    </div>

    <SurveyModal
        v-if="showSurveyModal && surveyDetails && selectedDomainId"
        :survey="surveyDetails"
        :domain-id="selectedDomainId"
        @close="handleCloseSurveyModal"
        @reset="handleResetDomain"
    />
</template>
