<script setup>

import BaseIconGenerator from "@/js/components/global/BaseIconGenerator.vue";
import DepartmentApprovedSolo from "@/js/components/svg/DepartmentApprovedSolo.vue";
import DeptApprovedIcon from "@/js/components/svg/software/DeptApprovedIcon.vue"
import DeptNegotiatedIcon from "@/js/components/svg/software/DeptNegotiatedIcon.vue";
import DeptProvidedIcon from '@/js/components/svg/software/DeptProvidedIcon.vue'

const props = defineProps({
    iconName:{
        type: String,
        required: true
    }
})
const adviceIconComponents = {
    'No cost':DeptApprovedIcon,
    'Cyber Assessed': DeptProvidedIcon,
    'Negotiated Deals': DepartmentApprovedSolo

}

</script>
<template>
    <template v-if="iconName">
        <BaseIconGenerator
            :icon-path="iconName"
            :components-object="adviceIconComponents"
        />
    </template>
</template>