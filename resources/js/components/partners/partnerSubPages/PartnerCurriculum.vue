<script setup>
import {ref, computed} from 'vue'
import AdviceCard from "@/js/components/advice/AdviceCard.vue";

const props = defineProps({
    data:{
        type: Object,
        required: true
    }
})

const emits = defineEmits([])
const fulfilledData = await props.data

</script>

<template>
    <div class="adviceSubPageContainer flex flex-col px-10">
        <div class="adviceSubpage Title">
            <div class="font-semibold text-2xl text-center pb-6">
                Here is your listed advice
            </div>
        </div>
        <div class="flex flex-row flex-wrap gap-6">
            <AdviceCard
                v-for="(item, index) in fulfilledData"
                :key="index"
                :advice-content="item"
                :number-per-row="3"
            />
        </div>
    </div>
</template>
