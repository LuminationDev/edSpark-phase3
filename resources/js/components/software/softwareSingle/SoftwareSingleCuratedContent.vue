<script setup>
import {useSoftwareStore} from "@/js/stores/useSoftwareStore";
import {storeToRefs} from "pinia";
import {computed, onMounted} from "vue";
import SoftwareCard from "@/js/components/software/SoftwareCard.vue";
import {useRoute} from "vue-router";

const route = useRoute()
const softwareStore = useSoftwareStore()
const { relatedSoftware } = storeToRefs(softwareStore)

</script>
<template>
    <div
        v-if="relatedSoftware && relatedSoftware.length > 0"
        class="bg-secondary-coolGrey/20 flex justify-center items-center flex-col mt-8 px-4 py-10 rounded softwareSingleCuratedContentContainer xl:!ml-4 xl:!px-10"
    >
        <div class="curatedResourcesTitle font-bold pb-8 text-2xl text-center">
            RELATED
        </div>
        <div class="flex flex-col gap-10 lg:!flex-row xl:!flex-col">
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