<script setup>
import { computed } from 'vue'

import ErrorIconSmall from "@/js/components/svg/ErrorIconSmall.vue";
import WarningIconSmall from "@/js/components/svg/WarningIconSmall.vue";


const props = defineProps({
    v$: {
        type: Object,
        required: true
    }
})


defineEmits(['update:modelValue'])
</script>

<template>
    <div class="flex-col">
        <div
            v-if="v$ && v$.$error"
            class="flex flex-col"
        >
            <div
                v-for="(error, index) in v$.$errors"
                :key="index"
                class="flex items-center flex-row mt-2 text-red-500"
            >
                <ErrorIconSmall
                    class="fill-inactive h-5 max-w-none mr-2 shrink-0 stroke-inactive w-5"
                    alt="error icon"
                />
                <span class="text-sm">{{ error.$message }}</span>
            </div>
        </div>
        <div
            v-else
            class="h-4"
        />
    </div>
</template>
