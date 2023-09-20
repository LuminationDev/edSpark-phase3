<script setup lang="ts">
import {onBeforeMount, ref} from "vue";
import {formService} from "@/js/service/formService";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";

type EventType ={
    id: number,
    name: string,
    value: string

}

const props = defineProps({
    label: {
        type: String, required: false, default: "Select type"
    },
    // eventLocation: {
    //     type: Object as () => EventLocationType,
    //     required: true
    // }
})
const selectedEventType = ref(0)
const availableTypes = ref<EventType[]>([])



onBeforeMount(() => {
    formService.getTypes(API_ENDPOINTS.EVENT.FETCH_EVENT_TYPES).then(res => {
        availableTypes.value = res.data
    }).catch(err => {
        console.log(err)
    })
})



</script>

<template>
    <div class="TypeContainer flex flex-col mb-4">
        <div class="TypeLabel">
            {{ props.label }}
        </div>
        <div class="TypeCheckboxList flex justify-around flex-row">
            <select
                v-model="selectedEventType"
                class="rounded"
            >
                <option
                    v-for="(type,index) in availableTypes"
                    :key="index + type.id"
                    class="dropdownItems flex items-center flex-row"
                    :name="type.name"
                    :value="type.id"
                >
                    {{ type["name"] }}
                </option>
            </select>
        </div>
    </div>
</template>
