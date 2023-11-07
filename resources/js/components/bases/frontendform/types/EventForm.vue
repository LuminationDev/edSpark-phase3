<script setup lang="ts">
import {reactive} from "vue";

import BaseForm from "@/js/components/bases/frontendform/BaseForm.vue";
import ExtraContent from "@/js/components/bases/frontendform/ExtraContent.vue";
import {templates} from "@/js/components/bases/frontendform/templates/formTemplates";
import EventTypeLocationTime, {EventTypeLocationTimeType} from "@/js/components/selector/EventTypeLocationTime.vue";
import {formService} from "@/js/service/formService";
import {EventAdditionalData} from "@/js/types/EventTypes";


const addtEventData = reactive<EventAdditionalData>({
    extra_content: [],
    type: 0,
    location: {},
    start_date: new Date(),
    end_date: new Date()
})

const updateExtraContent = (content): void => {
    console.log('here is content')
    console.log(content)
    if (content) {
        addtEventData.extra_content = content
    }
}

const handleReceiveTypesLocationTime = (data: EventTypeLocationTimeType): void => {
    addtEventData.type = data.type
    addtEventData.location = data.location
    addtEventData.start_date = data.start_date
    addtEventData.end_date = data.end_date

}

const handleReceiveAddtContent = (data) => {
    addtEventData.type = data.type
    addtEventData.location = data.location
    addtEventData.start_date = data.start_date
    addtEventData.end_date = data.end_date
    if (data['extra_content']) {
        if(data['extra_content'][0] && data['extra_content'][0]['data']){ // if data is in Filament Format, transform to Simple
            addtEventData.extra_content =  formService.transformFilamentFormatToSimpleData(data['extra_content'])
        } else{ //Data is already in simple format
            addtEventData.extra_content = data['extra_content']
        }
    }
    if (data['type']) {
        addtEventData.type = data['type']
    }
}
</script>


<template>
    <BaseForm
        item-type="event"
        :additional-data="addtEventData"
        @base-emits-addt-content="handleReceiveAddtContent"
    >
        <template #itemType>
            <EventTypeLocationTime
                :selected-type="addtEventData.type"
                :type-location-time="addtEventData"
                label="Select Event type"
                @emit-event-type-location-time="handleReceiveTypesLocationTime"
            />
        </template>
        <template #extraContent>
            <ExtraContent
                :extra-content-data="addtEventData.extra_content"
                :available-templates="templates"
                @update-parent-extra-content="updateExtraContent"
            />
        </template>
    </BaseForm>
</template>
