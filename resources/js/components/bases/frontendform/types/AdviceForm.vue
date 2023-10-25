<script setup lang="ts">
import {reactive} from "vue";

import BaseForm from "@/js/components/bases/frontendform/BaseForm.vue";
import ExtraContent from "@/js/components/bases/frontendform/ExtraContent.vue";
import {templates} from "@/js/components/bases/frontendform/templates/formTemplates";
import ItemTypeCheckboxes from "@/js/components/selector/ItemTypeCheckboxes.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {AdviceAdditionalData} from "@/js/types/AdviceTypes";


const addtAdviceData = reactive<AdviceAdditionalData>({
    extraContentData: [], 
    adviceTypes: [],

})

const updateExtraContent = (content): void => {
    if (content) {
        addtAdviceData.extraContentData = content
    }
}

const handleReceiveTypes = (typeArray): void => {
    addtAdviceData.adviceTypes = typeArray
}
</script>


<template>
    <BaseForm
        item-type="advice"
        :additional-data="addtAdviceData"
    >
        <template #itemType>
            <ItemTypeCheckboxes
                :type-api-link="API_ENDPOINTS.ADVICE.FETCH_ADVICE_TYPES"
                label="Select advice type"
                @send-selected-types-as-array="handleReceiveTypes"
            />
        </template>
        <template #extraContent>
            <ExtraContent
                :extra-content-data="addtAdviceData.extraContentData"
                :available-templates="templates"
                @update-parent-extra-content="updateExtraContent"
            />
        </template>
    </BaseForm>
</template>
