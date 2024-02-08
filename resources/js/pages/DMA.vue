<script setup>
import {computed, onMounted, ref, toRaw} from 'vue';

import BaseLandingHero from "@/js/components/bases/BaseLandingHero.vue";
import BaseLandingSection from "@/js/components/bases/BaseLandingSection.vue";
import OverlayModal from "@/js/components/bases/OverlayModal.vue";
import DomainSummary from "@/js/components/dma/DomainSummary.vue";
import SurveyModal from "@/js/components/dma/SurveyModal.vue";
import InspirationAndGuidesRobot from "@/js/components/inspirationandguides/InspirationAndGuidesRobot.vue";
import {LandingHeroText} from "@/js/constants/PageBlurb";
import {dmaService} from "@/js/service/dmaService";

const showSurveyModal = ref(false);

// TODO survey details populated by API
const surveyDetails = ref(null);

const selectedDomainId = ref(null);
const resettingDomainId = ref(null);

onMounted(async () => {
    await fetchUserSurvey();
})

const domains = computed(() => {
    console.log("DOMAINS", surveyDetails.value.survey_domains);
    if (!surveyDetails.value) return [];
    return surveyDetails.value.survey_domains;
});

const fetchUserSurvey = async () => {
    surveyDetails.value = await dmaService.getSurvey();
}

const handleLaunchSurvey = (domainId) => {
    console.log("Launch survey for domain", domainId);
    selectedDomainId.value = domainId;
    showSurveyModal.value = true;
}

const handleCloseSurveyModal = () => {
    showSurveyModal.value = false;
    // refetch survey data
    fetchUserSurvey();
}

const handleResetDomain = (domainId) => {
    showSurveyModal.value = false;
    resettingDomainId.value = domainId;
    setTimeout(() => {
        resettingDomainId.value = null
        // refetch survey data
        fetchUserSurvey();
    }, 4000);
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
                        class="DMADomainContainer grid grid-cols-1 gap-10"
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
                        </p>
                    </div>

                    <div
                        class="DMADomainContainer grid grid-cols-1 gap-10"
                    >
                        <DomainSummary
                            v-for="domain of domains"
                            :key="domain.id"
                            :domain="domain"
                            :resetting="resettingDomainId === domain.id"
                            @click="handleLaunchSurvey(domain.id)"
                        />
                    </div>
                </div>
            </template>
        </BaseLandingSection>
    </div>

    <OverlayModal
        v-if="showSurveyModal"
    >
        <template #content>
            <SurveyModal
                v-if="surveyDetails && selectedDomainId"
                :survey="surveyDetails"
                :domain-id="selectedDomainId"
                @close="handleCloseSurveyModal"
                @reset="handleResetDomain(selectedDomainId)"
            />
        </template>
    </OverlayModal>
</template>
