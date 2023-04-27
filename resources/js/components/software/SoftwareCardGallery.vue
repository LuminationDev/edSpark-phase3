<script setup>
import axios from 'axios'
import {onBeforeMount, ref} from "vue";
import {serverURL} from "@/js/constants/serverUrl";
import SoftwareCard from "@/js/components/software/SoftwareCard.vue";
import {axiosFetcher} from "@/js/helpers/fetcher";
import useSWRV from "swrv";

const {data: softwareList , error} = useSWRV(`${serverURL}/fetchSoftwarePosts`, axiosFetcher)

</script>
<template>
    <template v-if="softwareList">
        <div class="flex flex-row flex-wrap w-full justify-between pt-10 gap-6 px-20">
            <SoftwareCard
                v-for="(software,index) in softwareList"
                :key="index"
                :software="software"
                :number-per-row="4"
            />
        </div>
    </template>
    <template v-else>
        Loading
    </template>
</template>
