<script setup>
import { computed } from 'vue'
import WarningIconSmall from "@/js/components/svg/WarningIconSmall.vue";
import ErrorIconSmall from "@/js/components/svg/ErrorIconSmall.vue";


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
                class="flex flex-row items-center text-red-500 mt-2"
            >
                <ErrorIconSmall
                    class="w-4 h-4 stroke-inactive fill-inactive shrink-0 max-w-none mr-2"
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
