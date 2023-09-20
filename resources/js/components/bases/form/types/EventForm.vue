<script setup lang="ts">
import BaseForm from "@/js/components/bases/form/BaseForm.vue";
import ExtraContent from "@/js/components/bases/form/ExtraContent.vue";
import {reactive} from "vue";
import {templates} from "@/js/components/bases/form/templates/formTemplates";
import EventTypeLocation from "@/js/components/selector/EventTypeLocation.vue";

interface EventLocationType {
    url? : string,
    address?: string
}

type EventAdditionalData = {
    extraContentData : Array<any>,
    eventType: number,
    eventLocation: EventLocationType
    startTime: Date,
    endTime : Date
}

const addtEventData = reactive<EventAdditionalData>({
    extraContentData: [],
    eventType : 0,
    eventLocation: {},
    startTime: new Date(),
    endTime: new Date()

})

const updateExtraContent = (content) : void => {
    if (content) {
        addtEventData.extraContentData = content
    }
}

const handleReceiveTypes = (typeId : number) : void => {
    addtEventData.eventType = typeId
}
</script>


<template>
    <BaseForm
        item-type="event"
        :additional-data="addtEventData"
    >
        <template #itemType>
            <EventTypeLocation
                label="Select Event type"
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
