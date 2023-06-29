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
    <div class="softwareSubPageContainer flex flex-col px-10">
        <div class="softwareSubpage Title">
            <div class="font-semibold text-2xl text-center pb-6">
                Here are your listed softwares
            </div>
        </div>
        <div class="flex flex-row flex-wrap gap-6">
            <SoftwareCard
                v-for="(item, index) in fulfilledData"
                :key="index"
                :software="item"
                :number-per-row="3"
            />
        </div>
    </div>
</template>
