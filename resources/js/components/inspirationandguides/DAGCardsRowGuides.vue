<script setup>
import {computed,ref} from 'vue'

import AdviceCard from "@/js/components/advice/AdviceCard.vue";
import CardLoading from "@/js/components/card/CardLoading.vue";
import {getNRandomElementsFromArray} from "@/js/helpers/cardDataHelper";

import {useWindowStore} from "@/js/stores/useWindowStore";
const windowStore = useWindowStore()

const props = defineProps({
    adviceList:{
        type: Array,
        required: true
    }
})


const DAGAdviceList = computed(() =>{
    if(!props.adviceList || props.adviceList.length < 1 ) return []
    return props.adviceList.filter(advice => advice.type[0] === 'DAG')
})
</script>

<template>
    <div class=":!grid-cols-2 EduAdviceCards grid grid-cols-1 gap-10 place-items-center md:!grid-cols-2 lg:!grid-cols-3">
        <template v-if="props.adviceList && props.adviceList.length && windowStore.isMed">
            <AdviceCard
                v-for="advice in getNRandomElementsFromArray(DAGAdviceList,2)"
                :key="advice.guid"
                :data="advice"
                :show-icon="true"
            />
        </template>
        <template v-else-if="props.adviceList && props.adviceList.length && !windowStore.isMed">
            <AdviceCard
                v-for="advice in getNRandomElementsFromArray(DAGAdviceList,3)"
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
