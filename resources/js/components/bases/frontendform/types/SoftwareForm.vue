<script setup lang="ts">
import useVuelidate from "@vuelidate/core";
import {required} from "@vuelidate/validators";
import {reactive} from "vue";

import BaseForm from "@/js/components/bases/frontendform/BaseForm.vue";
import ExtraContent from "@/js/components/bases/frontendform/ExtraContent.vue";
import {templates} from "@/js/components/bases/frontendform/templates/formTemplates";
import ItemTypeCheckboxes from "@/js/components/selector/ItemTypeCheckboxes.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {formService} from "@/js/service/formService";
import {SoftwareAdditionalData} from "@/js/types/SoftwareTypes";


const addtSoftwareData = reactive<SoftwareAdditionalData>({
    extra_content: [],
    type : []

})
const rules = {
    extra_content:{},
    type: {required}
}

const v$ = useVuelidate(rules, addtSoftwareData)


const updateExtraContent = (content) : void => {
    if (content) {
        addtSoftwareData.extra_content = content
    }
}

const handleReceiveTypes = (typeArray): void => {
    addtSoftwareData.type = typeArray
}

const handleReceiveAddtContent = (data) => {
    if (data['extra_content']) {
        if(data['extra_content'][0] && data['extra_content'][0]['data']){ // if data is in Filament Format, transform to Simple
            addtSoftwareData.extra_content =  formService.transformFilamentFormatToSimpleData(data['extra_content'])
        } else{ //Data is already in simple format
            addtSoftwareData.extra_content = data['extra_content']
        }
        console.log(addtSoftwareData.extra_content)
    }
    if (data['type']) {
        addtSoftwareData.type = data['type']
    }
}
</script>


<template>
    <BaseForm
        item-type="software"
        :additional-data="addtSoftwareData"
        :additional-validation="v$"
        @base-emits-addt-content="handleReceiveAddtContent"
    >
        <template #itemType>
            <ItemTypeCheckboxes
                :selected-type="addtSoftwareData.type"
                :type-api-link="API_ENDPOINTS.SOFTWARE.FETCH_SOFTWARE_TYPES"
                label="Select software type"
                @send-selected-types-as-array="handleReceiveTypes"
            />
        </template>
        <template #extraContent>
            <ExtraContent
                :extra-content-data="addtSoftwareData.extra_content"
                :available-templates="templates"
                @update-parent-extra-content="updateExtraContent"
            />
        </template>
    </BaseForm>
</template>
