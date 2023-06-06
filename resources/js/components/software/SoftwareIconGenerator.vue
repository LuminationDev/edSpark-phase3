<script setup>

import BaseIconGenerator from "@/js/components/global/BaseIconGenerator.vue";
import DeptApprovedIcon from "@/js/components/svg/software/DeptApprovedIcon.vue"
import DeptProvidedIcon from '@/js/components/svg/software/DeptProvidedIcon.vue'
import DeptNegotiatedIcon from "@/js/components/svg/software/DeptNegotiatedIcon.vue";

const props = defineProps({
    iconName:{
        type: String,
        required: true
    }
})
const adviceIconComponents = {
    'Department Approved':DeptApprovedIcon,
    'Department Provided': DeptProvidedIcon,
    'Approved and Negotiated': DeptNegotiatedIcon

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