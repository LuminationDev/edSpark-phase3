<script setup lang="ts">
import BaseForm from "@/js/components/bases/form/BaseForm.vue";
import ExtraContent from "@/js/components/bases/form/ExtraContent.vue";
import ItemTypeCheckboxes from "@/js/components/selector/ItemTypeCheckboxes.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {reactive} from "vue";
import {templates} from "@/js/components/bases/form/templates/formTemplates";


const addtSoftwareData = reactive({
    extraContentData: [],
    selectedSoftwareTypes : []

})

const updateExtraContent = (content) : void => {
    if (content) {
        addtSoftwareData.extraContentData = content
    }
}

const handleReceiveTypes = (typeArray): void => {
    addtSoftwareData.selectedSoftwareTypes = typeArray
}
</script>


<template>
    <BaseForm
        item-type="software"
        :additional-data="addtSoftwareData"
    >
        <template #itemType>
            <ItemTypeCheckboxes
                :type-api-link="API_ENDPOINTS.SOFTWARE.FETCH_SOFTWARE_TYPES"
                label="Select software type"
                @send-selected-types-as-array="handleReceiveTypes"
            />
        </template>
        <template #extraContent>
            <ExtraContent
                :extra-content-data="addtSoftwareData.extraContentData"
                :available-templates="templates"
                @update-parent-extra-content="updateExtraContent"
            />
        </template>
    </BaseForm>
</template>
