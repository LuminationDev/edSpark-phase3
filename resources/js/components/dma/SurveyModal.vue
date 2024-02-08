<script setup>
import {computed, onMounted, ref} from "vue";

import CloseButton from "@/js/components/button/CloseButton.vue";
import DomainCoverScreen from "@/js/components/dma/DomainCoverScreen.vue";
import DomainFlow from "@/js/components/dma/DomainFlow.vue";
import TriageFlow from "@/js/components/dma/TriageFlow.vue";
import Spinner from "@/js/components/spinner/Spinner.vue";

const props = defineProps({
    domainId: {
        type: Number,
        required: true,
    },
    survey: {
        type: Object,
        required: true,
    }
})

const emit = defineEmits(['close','reset']);

// showTriage is initially null (loading)
const showTriage = ref(null);

const domain = computed(() => {
    return props.survey.survey_domains.find(d => d.id === props.domainId);
})

onMounted(() => {
    // set up initial state of survey:

    // check if triage has been performed
    if (props.survey.triage_domain.completed_question_count !== props.survey.triage_domain.question_count) {
        showTriage.value = true;
    } else {
        showTriage.value = false;
    }
});

const handleCompleteTriage = () => {
    showTriage.value = false;
}

</script>

<template>
    <!-- TODO correct dimensions of modal to be defined -->
    <div class="bg-black h-full max-h-[800px] max-w-5xl relative text-white w-full">
        <CloseButton
            class="absolute top-10 left-10"
            @click="emit('close')"
        />
        <div class="h-full">
            <div
                v-if="showTriage === null"
                class="flex justify-center items-center h-full w-full"
            >
                <Spinner />
            </div>
            <template v-else>
                <TriageFlow
                    v-if="showTriage"
                    :survey-id="props.survey.survey_id"
                    :domain-id="props.survey.triage_domain.id"
                    @complete="handleCompleteTriage"
                />
                <DomainFlow
                    v-else
                    :survey-id="props.survey.survey_id"
                    :domain="domain"
                    @complete="emit('close')"
                    @reset="emit('reset')"
                />
            </template>
        </div>
    </div>
</template>
