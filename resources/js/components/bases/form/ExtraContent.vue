<script setup>
import ExtraResource from "@/js/components/bases/form/ExtraResource.vue";
import ExtraTemplate from "@/js/components/bases/form/ExtraTemplate.vue";
import {ref, computed} from 'vue'

const props = defineProps({
    resourceData: {
        type: Object, required: false, default: () => {}
    },
    templateData: {
        type: Object, required: false, default: () => {}
    }

})

const emits = defineEmits([])
</script>

<template>
    <div class="border-2 border-gray-300 formExtraContentContainer p-2">
        <div class="formExtraContentHeading text-lg">
            Extra Content
        </div>
        <div class="formExtraContentBody">
            <ExtraResource :data="resourceData" />
            <ExtraTemplate :data="templateData" />
        </div>
    </div>
</template>
