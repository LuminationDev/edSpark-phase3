<script setup>
import {computed, onMounted, ref} from "vue";

import CloseButton from "@/js/components/dma/CloseButton.vue";
import DomainFlow from "@/js/components/dma/DomainFlow.vue";
import OverlayModal from "@/js/components/dma/OverlayModal.vue";
import TriageFlow from "@/js/components/dma/TriageFlow.vue";
import Spinner from "@/js/components/spinner/Spinner.vue";
import {dmaService} from "@/js/service/dmaService";

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

const domain = computed(() => {
    return props.survey.survey_domains.find(d => d.id === props.domainId);
})

const triageDomain = computed(() => {
    return props.survey.survey_domains.find(d => d.domain === 'triage');
});

// showTriage is initially null (loading)
const showTriage = ref(null);


onMounted(async () => {
    // set up initial state of survey:

    if (triageDomain.value) {
        if (triageDomain.value.completed_question_count !== triageDomain.value.question_count) {
            showTriage.value = true;
        } else {
            showTriage.value = false;
        }
    }
});

const handleCompleteTriage = () => {
    showTriage.value = false;
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
            <div class="h-full overflow-y-scroll md:!overflow-y-hidden">
                <div
                    v-if="showTriage === null"
                    class="flex justify-center items-center min-h-full w-full md:h-full"
                >
                    <Spinner />
                </div>
                <template v-else>
                    <TriageFlow
                        v-if="showTriage"
                        :survey-id="props.survey.survey_id"
                        :domain="triageDomain"
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
    </OverlayModal>
</template>
