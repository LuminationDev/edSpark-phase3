<script setup lang="ts">
import {computed,ref} from 'vue'

import {CatalogueItemType} from "@/js/types/catalogueTypes";

const props = defineProps({
    itemData:{
        type: {} as () => CatalogueItemType,
        required: true
    }
})

const emits = defineEmits([])
</script>

<template>
    <div class="border-[1px] border-slate-300 mb-4 px-4 py-2 rounded shadow">
        {{ props.itemData.name }}
    </div>
</template>
