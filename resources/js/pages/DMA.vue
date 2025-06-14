<script setup>
import "@/css/dma/index.css";

import {computed, onMounted, ref} from 'vue';

import BaseLandingHero from "@/js/components/bases/BaseLandingHero.vue";
import BaseLandingSection from "@/js/components/bases/BaseLandingSection.vue";
import GenericButton from '@/js/components/button/GenericButton.vue';
import CircleDiagram from "@/js/components/dma/CircleDiagram.vue";
import DomainSummary from "@/js/components/dma/DomainSummary.vue";
import FaqEntry from "@/js/components/dma/FaqEntry.vue";
import ReportModal from "@/js/components/dma/ReportModal.vue";
import RoundButton from "@/js/components/dma/RoundButton.vue";
import SurveyModal from "@/js/components/dma/SurveyModal.vue";
import WarningModal from "@/js/components/dma/WarningModal.vue";
import InspirationAndGuidesRobot from "@/js/components/inspirationandguides/InspirationAndGuidesRobot.vue";
import {LandingHeroText} from "@/js/constants/PageBlurb";
import {formatDateToDayTime} from "@/js/helpers/dateHelper";
import {dmaService} from "@/js/service/dmaService";

const showSurveyModal = ref(false);
const showReportModal = ref(false);

const surveyDetails = ref(null);

const selectedDomainId = ref(null);
const resetting = ref(false);

const showResetModal = ref(false);
const showStartNewSurveyModal = ref(false);
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
                    element = {name: result.element, scores: [], completed: false};
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
                if (element.completed) {
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
    await new Promise(r => setTimeout(r, 3500));
    await fetchUserSurvey();
    await new Promise(r => setTimeout(r, 500));
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
    showStartNewSurveyModal.value = false
    dmaService.resetSurveyProgress().then(() => {
        showResetting();
    }).catch((error) => {
        console.log("API error resetting survey:", error);
        showErrorModal.value = true;
    })
}

const surveyInfo = computed(() => {
    if (surveyDetails.value && surveyDetails.value.survey_info) {
        return {
            created_by: surveyDetails.value.survey_info.survey_created_by,
            created_date: surveyDetails.value.survey_info.survey_created_date,
            site_name: surveyDetails.value.survey_info.survey_site_name
        }

    } else {
        return false
    }
})


</script>

<template>
    <div>
        <BaseLandingHero
            :title="LandingHeroText['dma']['title']"
            :title-paragraph="LandingHeroText['dma']['subtitle']"
            swoosh-color="teal"
        >
            <template #robotIllustration>
                <InspirationAndGuidesRobot class="absolute top-16 left-36" />
            </template>
        </BaseLandingHero>
        <div class="dma-app-root">
            <BaseLandingSection
                background-color="white"
            >
                <template #title>
                    <h2 class="mb-4 px-4 text-h2 md:text-h3 md:!px-5 lg:!px-0">
                        Your DMA
                    </h2>
                    <div
                        v-if="surveyInfo"
                        class="flex flex-col subtitle text-lg"
                    >
                        <div>Created by: {{ surveyInfo.created_by }}</div>
                        <div>Created on: {{ formatDateToDayTime(surveyInfo.created_date) }}</div>
                        <div>Site name: {{ surveyInfo.site_name }}</div>
                    </div>
                </template>
                <template #content>
                    <div
                        v-if="showSurveyError"
                        class="font-bold my-2"
                    >
                        There was a network error obtaining your latest assessment data.
                        <button
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
                                        class="font-thin text-lg"
                                    >
                                        This chart shows your performance in each of the assessed elements.
                                    </p>
                                    <p
                                        v-else
                                        class="font-thin text-lg"
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
                                        class="!normal-case
                                            
                                            
                                            hover:!bg-secondary-coolGrey
                                            hover:!brightness-[1.1]"
                                        @click="showReportModal = true"
                                    >
                                        View assessment report
                                    </RoundButton>
                                </div>
                            </div>
                            <p class="font-thin px-4 text-lg md:!px-5 lg:!px-0">
                                <!-- TODO correct this information -->

                                The tool is for you and your school. Your data is stored on the edSpark platform.
                                You can generate a PDF report below for sharing within your school or including in your
                                next
                                round of School Improvement planning.


                                <!-- <span class="flex flex-row w-full gap-10 justify-between mt-6"> -->


                                <span class="flex justify-between flex-row h-10 mt-7">
                                    <GenericButton
                                        v-if="isInProgress && !isDomainResetting()"
                                        class="
                                            !text-black
                                            bg-secondary-coolGrey
                                            brightness-[1.1]
                                            font-medium
                                            px-12
                                            py-2
                                            text-lg
                                            hover:!bg-secondary-coolGrey
                                            hover:!brightness-[1.2]
                                            "
                                        @click="showResetModal = true"
                                    >
                                        Reset progress
                                    </GenericButton>
                                    <GenericButton
                                        v-if="isInProgress && !isDomainResetting() && isCompleted"
                                        class="
                                            !text-black
                                            bg-secondary-coolGrey
                                            brightness-[1.1]
                                            font-medium
                                            px-12
                                            py-2
                                            text-lg
                                            hover:!bg-secondary-coolGrey
                                            hover:!brightness-[1.2]
                                            "
                                        @click="showStartNewSurveyModal = true"
                                    >
                                        Start over
                                    </GenericButton>
                                </span>

                                <!-- </span> -->
                            </p>
                        </div>

                        <div
                            class="DMADomainContainer flex flex-col gap-5 px-4 md:!gap-6 md:!px-5 lg:!gap-8 lg:!px-0"
                        >
                            <DomainSummary
                                v-for="domain of domains"
                                id="DomainSummary"
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
                        @confirm="handleResetSurvey"
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
                    <WarningModal
                        v-if="showStartNewSurveyModal"
                        @cancel="showStartNewSurveyModal=false"
                        @confirm="handleResetSurvey"
                    >
                        <template #title>
                            Are you sure?
                        </template>
                        <template #message>
                            Starting over will mark your old survey as superseded and present a new survey.
                        </template>
                        <template #confirm>
                            Start over
                        </template>
                    </WarningModal>
                </template>
            </BaseLandingSection>

            <BaseLandingSection>
                <template #title>
                    <h2 class="px-4 text-h3 md:!px-5 lg:!px-0">
                        Frequently asked
                    </h2>
                </template>
                <!-- <template #subtitle>
                    <div class="mb-10 px-4 md:!px-5 lg:!px-0">
                        The Digital Adoption Group (DAG) offers comprehensive guidance on digital technologies,
                        providing practical, system-wide advice for purchasing and adopting high-impact technologies
                        that enhance teaching and learning.
                    </div>
                </template> -->
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
                                Your Digital Maturity Assessment data is stored on the edSpark platform, which is
                                managed by the SA Department for Education
                                in accordance with the <a
                                    href="https://www.education.sa.gov.au/your-privacy"
                                    target="_blank"
                                >privacy policy</a>. The edSpark
                                platform is integrated with edPass, and makes use of relevant information including but
                                not limited to your role, site name and other details on an as-needed basis.
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
