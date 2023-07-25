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
    <div class="hardwareSubPageContainer flex flex-col px-10">
        <div class="hardwareSubpage Title">
            <div class="font-semibold text-2xl text-center pb-6">
                Here is your listed hardware
            </div>
        </div>
        <div class="flex flex-row flex-wrap gap-4">
            <HardwareCard
                v-for="(item, index) in fulfilledData"
                :key="index"
                :hardware-content="item"
                :number-per-row="3"
            />
        </div>
    </div>
</template>
