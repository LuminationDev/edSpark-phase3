<script setup>
import {useSoftwareStore} from "@/js/stores/useSoftwareStore";
import {storeToRefs} from "pinia";
import {computed, onMounted} from "vue";
import SoftwareCard from "@/js/components/software/SoftwareCard.vue";
import {useRoute} from "vue-router";

const route = useRoute()
const softwareStore = useSoftwareStore()
const { recommendedSoftware } = storeToRefs(softwareStore)

</script>
<template>
    <div
        v-if="recommendedSoftware && recommendedSoftware.length > 0"
        class="bg-purple-50 flex justify-center items-center flex-col px-5 rounded softwareSingleCuratedContentContainer xl:!ml-4 xl:!px-10"
    >
        <div class="curatedResourcesTitle font-bold my-2 py-4 text-2xl text-center uppercase">
            RELATED
        </div>
        <div class="flex-col lg:!flex-row xl:!flex-col">
            <SoftwareCard
                v-for="software in recommendedSoftware"
                :key="software.guid"
                :data="software"
                :show-icon="true"
                :number-per-row="2"
                class="mb-4"
            />
        </div>
    </div>
    <div v-else>
        Loading
    </div>
</template>