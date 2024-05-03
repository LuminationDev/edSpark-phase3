<script setup lang="ts">
import {CatalogueGroupedItemType, catalogueTableHeaders} from "@/js/types/catalogueTypes";

const props = defineProps({
    data:{
        type: Array as () => CatalogueGroupedItemType[][],
        required: true
    }
})


</script>

<template>
    <template
        v-for="(header,index) in catalogueTableHeaders"
        :key="index"
    >
        <div class="border-[1px] border-slate-300 flex flex-col h-fit mb-4 rounded-lg tableOuter w-full">
            <div class="capitalize px-4 py-2 text-lg text-main-darkTeal">
                {{ header.replace(/_/g, " ") }}
            </div>
            <div
                v-for="(eachItemGroupedData,idx) in props.data"
                :key="idx"
            >
                <div
                    v-for="(row, indx) in eachItemGroupedData.filter(item => item.group === header)"
                    :key="`${indx}-row`"
                    class="px-4 py-2 even:bg-main-teal/5"
                >
                    <div class="grid grid-cols-2 py-1 w-full">
                        <div>
                            {{ row.display_text }}
                        </div>
                        <div class="text-center text-slate-600">
                            {{ row.value }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</template>
