<script setup lang="ts">
import {reactive} from "vue";

import BaseForm from "@/js/components/bases/form/BaseForm.vue";
import ExtraContent from "@/js/components/bases/form/ExtraContent.vue";
import {templates} from "@/js/components/bases/form/templates/formTemplates";
import EventTypeLocationTime from "@/js/components/selector/EventTypeLocationTime.vue";
import {EventAdditionalData} from "@/js/types/EventTypes";



const addtEventData = reactive<EventAdditionalData>({
    extraContentData: [],
    eventType: 0,
    eventLocation: {},
    startTime: new Date(),
    endTime: new Date()

})

const updateExtraContent = (content): void => {
    if (content) {
        addtEventData.extraContentData = content
    }
}

const handleReceiveTypesLocationTime = (data: object): void => {
    addtEventData.eventType = data.eventType
    addtEventData.eventLocation = data.eventLocation
    addtEventData.startTime = data.startTime
    addtEventData.endTime = data.endTime
    if (data.extraContentData) {
        addtEventData.extraContentData = data.extraContentData
    }
}
</script>


<template>
    <BaseForm
        item-type="event"
        :additional-data="addtEventData"
        @base-emits-addt-content="handleReceiveTypesLocationTime"
    >
        <template #itemType>
            <EventTypeLocationTime
                :type-location-time="addtEventData"
                label="Select Event type"
                @emit-event-type-location-time="handleReceiveTypesLocationTime"
            />
        </template>
        <template #extraContent>
            <ExtraContent
                :extra-content-data="addtEventData.extraContentData"
                :available-templates="templates"
                @update-parent-extra-content="updateExtraContent"
            />
        </template>
    </BaseForm>
</template>
