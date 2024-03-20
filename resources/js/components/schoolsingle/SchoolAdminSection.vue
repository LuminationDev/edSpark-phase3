<script setup>
import {computed, ref} from 'vue'

import GenericButton from "@/js/components/button/GenericButton.vue";

const props = defineProps({
    editMode:{
        type: Boolean,
        required: true
    },
    currentUserCanEdit:{
    }
})

const emits = defineEmits([])
</script>

<template>
    <div
        v-if="currentUserCanEdit"
        class="border-[1px] border-black flex flex-col mb-2 px-4 py-4 schoolAdminSection"
    >
        <h2 class="font-semibold mb-2 text-genericDark text-lg">
            Admin Sections
        </h2>
        <p class="mb-2">
            Your Role
        </p>
        <p
            class="mb-2"
        >
            Moderation Status
        </p>
        <GenericButton
            v-if="!editMode "
            class="bg-blue-600 hover:bg-blue-400 px-6 py-2 rounded text-white w-48"
            :callback="handleEditButton"
        >
            Edit this page
        </GenericButton>
        <slot
            v-if="currentUserCanNominate"
            name="additionalContentActions"
        />
    </div>
</template>
