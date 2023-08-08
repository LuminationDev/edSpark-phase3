<script setup>
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {computed} from 'vue'
import useSWRV from "swrv"
import {serverURL} from "@/js/constants/serverUrl"
import {axiosFetcher} from "@/js/helpers/fetcher"
import HardwareCard from "@/js/components/hardware/HardwareCard.vue";

const {data: softwareList , error} = useSWRV(API_ENDPOINTS.HARDWARE.FETCH_HARDWARE_POSTS, axiosFetcher)

const laptopData = computed(() => {
    return softwareList.value?.filter(item =>  item.category['categoryName'] == "Laptop") || []
})

</script>
<template>
    <div
        v-if="laptopData"
        class="Card HardwareLaptopSectionCantainer flex flex-row gallery gap-6"
    >
        <div
            v-for="(hardware,index) in laptopData"
            :key="index"
            class="hardwareCard"
        >
            <HardwareCard
                :hardware-content="hardware"
                number-per-row="4"
            />
        </div>
    </div>
</template>