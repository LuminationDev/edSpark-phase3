<script setup>
import axios from 'axios'
import {onBeforeMount, ref} from "vue";
import {serverURL} from "@/js/constants/serverUrl";
import SoftwareCard from "@/js/components/software/SoftwareCard.vue";

const softwareList = ref([])

onBeforeMount(() => {
    axios.get(`${serverURL}/fetchSoftwarePosts`).then(res => {
        softwareList.value = res.data
        console.log(res.data)
    }).catch(err => {
        console.error(err)
    })
})
</script>
<template>
    <template v-if="softwareList.length > 0">
        <div class="flex flex-row flex-wrap w-full justify-between pt-10 gap-6 px-20">
            <SoftwareCard
                v-for="(software,index) in softwareList"
                :key="index"
                :software="software"
            />
        </div>
    </template>
    <template v-else>
        Loading
    </template>
</template>
