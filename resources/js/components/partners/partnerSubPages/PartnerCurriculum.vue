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
        <div class="Title adviceSubpage">
            <div class="font-semibold pb-6 text-2xl text-center">
                Here is your listed advice
            </div>
        </div>
        <div class="flex justify-center lg:justify-start flex-row flex-wrap gap-6">
            <AdviceCard
                v-for="(item, index) in fulfilledData"
                :key="index"
                :data="item"
                :number-per-row="3"
            />
        </div>
    </div>
</template>
