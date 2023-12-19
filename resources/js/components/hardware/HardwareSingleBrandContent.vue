<script setup>
import HardwareCard from "@/js/components/hardware/HardwareCard.vue";
import {useHardwareStore} from "@/js/stores/useHardwareStore";
import {storeToRefs} from "pinia";

const hardwareStore = useHardwareStore()
const {relatedBrandHardware} = storeToRefs(hardwareStore)


</script>
<template>
    <div
        v-if="relatedBrandHardware && relatedBrandHardware.length > 0"
        class="bg-secondary-coolGrey/20 flex flex-col gap-6 p-4"
    >
        <h3 class="font-bold mx-auto text-[24px]">
            More from the same brand
        </h3>
        <div
            v-for="(item,index) in relatedBrandHardware"
            :key="index"
            class="flex justify-between flex-col gap-10"
        >
            <HardwareCard
                :key="index.guid"
                class="bg-white mx-auto"
                :data="item"
            />
        </div>
    </div>
</template>
