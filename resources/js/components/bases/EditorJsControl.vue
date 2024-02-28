<script setup lang="ts">



import {StateDescriptionType} from "@/js/types/EditorJsTypes";

const props = defineProps({
    currentState: {
        type: String,
        required: true
    },
    editorDescriptionByState: {
        type: Object as () => StateDescriptionType,
        required: true
    },
    buttonDescriptionByState: {
        type: Object as () => StateDescriptionType,
        required: true
    },
    saveFunction: {
        type: Function,
        required: true
    },
    editFunction: {
        type: Function, required: true
    },
    loadPendingFunction: {
        type: Function, required: true
    }
})

</script>

<template>
    <div class="flex flex-col">
        <div
            class="flex font-semibold mb-4 text-center"
        >
            {{ editorDescriptionByState[currentState] }}
        </div>
        <div class="flex flex-row gap-4">
            <button
                v-if="currentState === 'pending_available' || currentState === 'submitted_pending'"
                class="bg-blue-500 hover:bg-secondary-blueberry mb-4 px-6 py-2 rounded text-white w-48"
                @click="loadPendingFunction"
            >
                Edit pending content
            </button>
            <button
                class="bg-blue-500 hover:bg-secondary-blueberry mb-4 px-6 py-2 rounded text-white w-48"
                @click="saveFunction"
            >
                {{ buttonDescriptionByState[currentState] }}
            </button>
            <button
                v-if="currentState === 'pending_loaded'"
                class="bg-blue-500 hover:bg-secondary-blueberry mb-4 px-6 py-2 rounded text-white w-48"
                @click="editFunction"
            >
                Revert
            </button>
        </div>
    </div>
</template>
