<script setup>
import {computed, onMounted, ref} from 'vue'

import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {catalogueService} from "@/js/service/catalogueService";

const catalogueList = ref([]);


onMounted(() =>{
    catalogueService.fetchCatalogueByField('type', 'Notebook', 4,40).then(res =>{
        console.log(res.data.data)
        catalogueList.value = res.data.data.items
    })
})
</script>

<template>
    <div class="flex flex-row flex-wrap gap-4 mt-10">
        <div
            v-for="(item,index) in catalogueList"
            :key="index"
            class="border-[1px] rounded w-48"
        >
            <div class="font-semibold productName">
                {{ item.name }}
            </div>
            <div> {{ item.category }}</div>
        </div>
    </div>
</template>
