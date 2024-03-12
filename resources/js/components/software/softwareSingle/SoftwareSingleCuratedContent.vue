<script setup>
import {storeToRefs} from "pinia";
import {computed, onMounted} from "vue";
import {useRoute} from "vue-router";

import SoftwareCard from "@/js/components/software/SoftwareCard.vue";
import {useSoftwareStore} from "@/js/stores/useSoftwareStore";

const route = useRoute()
const softwareStore = useSoftwareStore()
const { relatedSoftware } = storeToRefs(softwareStore)

</script>
<template>
    <div
        v-if="relatedSoftware && relatedSoftware.length > 0"
        class="flex flex-col mt-8 py-6 rounded softwareSingleCuratedContentContainer"
    >
        <div class="curatedResourcesTitle font-bold pb-4 text-2xl text-center md:!text-left">
            Other similar resources
        </div>
        <div class="flex flex-col md:flex-row gap-10 items-center md:items-start">
            <SoftwareCard
                v-for="software in relatedSoftware"
                :key="software.guid"
                :data="software"
                :show-icon="true"
            />
        </div>
    </div>
    <div v-else>
        Loading
    </div>
</template>