<script setup lang="ts">
import {computed} from "vue";

import {catalogueComparisonHeaders,CatalogueGroupedItemType} from "@/js/types/catalogueTypes";

const props = defineProps({
    data: {
        type: Array as () => CatalogueGroupedItemType[][],
        required: true
    }
})

const tableData = computed(() => {
    return props.data
})

console.log(tableData.value)
/*
table data

header name : ['', someval,some val]
 */


const processedDataForTable = computed(() => {
    const result = {} // {brand : [ acer, acer, dell]}
    tableData.value[0].forEach(eaAttr => {
        const key = eaAttr.name
        result[key] = []
    })
    Object.keys(result).forEach(key => {
        tableData.value.forEach(item => {
            const attrVal = item.filter(attr => attr.name === key)[0]
            if (attrVal) {
                result[key].push(attrVal.value)

            } else {
                result[key].push('')
            }
        })
    })
    return result
})

const groupAttrName = computed(() => {
    const result = {}
    tableData.value[0].forEach(attr => {
        if(attr.group === 'hidden') return;
        if (!result[attr.group]) {
            result[attr.group] = []
        }

        result[attr.group].push(attr.name)
    })
    console.log(result)
    return result
})


</script>

<template>
    <template
        v-for="(header,index) in catalogueComparisonHeaders"
        :key="index"
    >
        <div class="flex flex-col h-fit mb-4 py-6 rounded-lg tableOuter w-full">
            <div class="capitalize px-4 py-2 text-lg text-main-darkTeal">
                {{ header.replace(/_/g, " ") }}
            </div>
            <div
                v-for="(objectData,idx) in Object.entries(processedDataForTable).filter(item => groupAttrName[header].includes(item[0]))"
                :key="idx"
                class="grid grid-cols-4 place-items-center py-4 text-slate-600 w-full even:bg-main-teal/5"
            >
                <div class="capitalize font-semibold">
                    {{ objectData[0].replace(/_/g, " ") }}
                </div>
                <div
                    v-for="(value, valueIdx) in objectData[1]"
                    :key="valueIdx"
                >
                    {{ value }}
                </div>
            </div>
        </div>
    </template>
</template>