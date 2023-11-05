<script setup lang="ts">
import {reactive} from "vue";

import BaseForm from "@/js/components/bases/frontendform/BaseForm.vue";
import ExtraContent from "@/js/components/bases/frontendform/ExtraContent.vue";
import {templates} from "@/js/components/bases/frontendform/templates/formTemplates";
import EventTypeLocationTime from "@/js/components/selector/EventTypeLocationTime.vue";
import {EventAdditionalData} from "@/js/types/EventTypes";


const addtEventData = reactive<EventAdditionalData>({
    extra_content: [],
    type: 0,
    location: {},
    start_date: new Date(),
    end_date: new Date()
})

const updateExtraContent = (content): void => {
    if (content) {
        addtEventData.extra_content = content
    }
}

const handleReceiveTypesLocationTime = (data: object): void => {
    addtEventData.type = data.eventType
    addtEventData.location = data.eventLocation
    addtEventData.start_date = data.startTime
    addtEventData.end_date = data.endTime
    if (data.extraContentData) {
        addtEventData.extra_content = data.extraContentData
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
                :extra-content-data="addtEventData.extra_content"
                :available-templates="templates"
                @update-parent-extra-content="updateExtraContent"
            />
        </template>
    </BaseForm>
</template>
