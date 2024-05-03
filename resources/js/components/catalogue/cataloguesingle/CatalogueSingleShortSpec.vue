<script setup>
import {computed,ref} from 'vue'

const props = defineProps({
    shortSpecEntries :{
        type: Array,
        required: true
    }
})

const emits = defineEmits([])
</script>

<template>
    <div class="catItemShortSpec flex flex-col">
        <div
            v-for="(desc,index) in shortSpecEntries"
            :key="index"
            class="flex flex-row mb-1"
        >
            <span
                v-if="desc[1]"
                class="capitalize font-semibold"
            >
                {{ desc[0] + ": " }}
            </span>
            <span class="ml-2 shortSpecValue text-slate-600">
                {{ desc[1] + " " }}

            </span>
        </div>
    </div>
</template>
