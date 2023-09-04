<script setup>
import {ref, computed} from 'vue'
import HardwareCard from "@/js/components/hardware/HardwareCard.vue";

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
    <div class="flex flex-col hardwareSubPageContainer px-10">
        <div class="Title hardwareSubpage">
            <div class="font-semibold pb-6 text-2xl text-center">
                Here is your listed hardware
            </div>
        </div>
        <div class="flex justify-center lg:justify-start flex-row flex-wrap  gap-4">
            <HardwareCard
                v-for="(item, index) in fulfilledData"
                :key="index"
                :data="item"
                :number-per-row="3"
            />
        </div>
    </div>
</template>
