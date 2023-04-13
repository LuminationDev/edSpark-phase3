<script setup>
import axios from 'axios'
import {onBeforeMount, ref} from "vue";
import {serverURL} from "@/js/constants/serverUrl";

const softwareList = ref([])

onBeforeMount(() => {
    axios.get(`${serverURL/fet}`)
})
</script>
<template>
    <div />
</template>