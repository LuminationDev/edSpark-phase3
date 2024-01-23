<script setup>
import {computed, onMounted, ref} from 'vue'
import {useRoute} from "vue-router";

import BaseHero from "@/js/components/bases/BaseHero.vue";
import BaseSingle from "@/js/components/bases/BaseSingle.vue";
import {catalogueImageURL} from "@/js/constants/serverUrl";
import {catalogueService} from "@/js/service/catalogueService";

const props = defineProps({})


const route = useRoute()
const uniqueRef = route.params.ref
const itemData = ref({})

onMounted(() => {
    catalogueService.fetchSingleProductByReference(uniqueRef).then(res => {
        console.log(res.data.data)
        itemData.value = res.data.data

    })
})
</script>

<template>
    <BaseHero
        :background-url="itemData.image"
        :background-server-url="catalogueImageURL"
    >
        <template #titleText>
            {{ itemData.name }}
        </template>
        <template #hardwareProvider>
            {{ itemData.vendor }}
        </template>
        <template #subtitleText2>
            Brand: {{ itemData.brand }}
        </template>
    </BaseHero>
    <div class="flex justify-center items-center">
        <table class="bg-white border border-gray-300">
            <thead>
                <tr>
                    <th class="bg-gray-100 border-b px-4 py-2">
                        Property
                    </th>
                    <th class="bg-gray-100 border-b px-4 py-2">
                        Value
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="(value, key) in itemData"
                    :key="key"
                >
                    <td class="border-b px-4 py-2">
                        {{ key }}
                    </td>
                    <td class="border-b px-4 py-2">
                        {{ value }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
