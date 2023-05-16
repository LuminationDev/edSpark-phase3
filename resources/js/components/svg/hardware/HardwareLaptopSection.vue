<script setup>
import {computed} from 'vue'
import useSWRV from "swrv"
import {serverURL} from "@/js/constants/serverUrl"
import {axiosFetcher} from "@/js/helpers/fetcher"
import HardwareCard from "@/js/components/hardware/HardwareCard.vue";

const {data: softwareList , error} = useSWRV(`${serverURL}/fetchAllProducts`, axiosFetcher)

const laptopData = computed(() => {
    return softwareList.value?.filter(item =>  item.category['categoryName'] == "Laptop") || []
})

</script>
<template>
    <div
        v-if="laptopData"
        class="HardwareLaptopSectionCantainer Card gallery flex flex-row"
    >
        <div
            v-for="(hardware,index) in laptopData"
            :key="index"
            class="hardwareCard"
        >
            <HardwareCard
                :hardware="hardware"
                number-per-row="4"
            />
        </div>
    </div>
</template>