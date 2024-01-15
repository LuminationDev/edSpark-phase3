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
import {AdviceAdditionalData} from "@/js/types/AdviceTypes";


const addtAdviceData = reactive<AdviceAdditionalData>({
    extra_content: [],
    type: [],

})
const rules = {
    extra_content:{},
    type: {required}
}

const v$ = useVuelidate(rules, addtAdviceData)

const updateExtraContent = (content): void => {
    if (content) {
        addtAdviceData.extra_content = content
    }
}

const handleReceiveTypes = (typeArray): void => {
    addtAdviceData.type = typeArray
}

const handleReceiveAddtContent = (data) => {
    if (data['extra_content']) {
        if(data['extra_content'][0] && data['extra_content'][0]['data']){ // if data is in Filament Format, transform to Simple
            addtAdviceData.extra_content =  formService.transformFilamentFormatToSimpleData(data['extra_content'])
        } else{ //Data is already in simple format
            addtAdviceData.extra_content = data['extra_content']
        }
        console.log(addtAdviceData.extra_content)
    }
    if (data['type']) {
        addtAdviceData.type = data['type']
    }
}
</script>


<template>
    <BaseForm
        item-type="advice"
        :additional-data="addtAdviceData"
        :additional-validation="v$"
        @base-emits-addt-content="handleReceiveAddtContent"
    >
        <template #itemType>
            <ItemTypeCheckboxes
                :selected-type="addtAdviceData.type"
                :type-api-link="API_ENDPOINTS.ADVICE.FETCH_ADVICE_TYPES"
                label="Guide type"
                :v$="v$.type"
                @send-selected-types-as-array="handleReceiveTypes"
            />
        </template>
        <template #extraContent>
            <ExtraContent
                :extra-content-data="addtAdviceData.extra_content"
                :available-templates="templates"
                @update-parent-extra-content="updateExtraContent"
            />
        </template>
    </BaseForm>
</template>
