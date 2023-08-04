<script setup>
import {ref, computed} from 'vue'
import SoftwareCard from "@/js/components/software/SoftwareCard.vue";

const props = defineProps({
    data:{
        type: Object,
        required: true
    }
})
const fulfilledData = await props.data
console.log(props.data)

</script>

<template>
    <div class="flex flex-col px-10 softwareSubPageContainer">
        <div class="Title softwareSubpage">
            <div class="font-semibold pb-6 text-2xl text-center">
                Here is your listed software
            </div>
        </div>
        <div class="flex justify-center lg:justify-start flex-row flex-wrap gap-6">
            <SoftwareCard
                v-for="(item, index) in fulfilledData"
                :key="index"
                :software="item"
                :number-per-row="3"
            />
        </div>
    </div>
</template>
