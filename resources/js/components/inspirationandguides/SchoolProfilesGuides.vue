<script setup>
import {computed,ref} from 'vue'

import CardLoading from "@/js/components/card/CardLoading.vue";
import CarouselGenerator from "@/js/components/card/CarouselGenerator.vue";
import SchoolCard from "@/js/components/schools/SchoolCard.vue";
import {getNRandomElementsFromArray} from "@/js/helpers/cardDataHelper";


import {useWindowStore} from "@/js/stores/useWindowStore";
const windowStore = useWindowStore()

const props = defineProps({
    schoolList:{
        type: Array,
        required: true
    }
})

</script>

<template>
    <div class="EduAdviceCards grid grid-cols-1 gap-10 place-items-center md:!grid-cols-2 lg:!grid-cols-3">
        <template v-if="props.schoolList && props.schoolList.length && windowStore.isMed">
            <SchoolCard
                v-for="advice in getNRandomElementsFromArray(schoolList,2)"
                :key="advice.guid"
                :data="advice"
                :show-icon="true"
            />
        </template>
        <template v-else-if="props.schoolList && props.schoolList.length && !windowStore.isMed">
            <SchoolCard
                v-for="advice in getNRandomElementsFromArray(schoolList,3)"
                :key="advice.guid"
                :data="advice"
                :show-icon="true"
            />
        </template>
        <template v-else>
            <div
                class="col-span-1 md:!col-span-2 lg:!col-span-3"
            >
                <CardLoading
                    :number-of-rows="1"
                    :number-per-row="3"
                />
            </div>
        </template>
    </div>
</template>
