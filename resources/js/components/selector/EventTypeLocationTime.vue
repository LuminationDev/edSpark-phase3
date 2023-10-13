<script setup lang="ts">
import useVuelidate from "@vuelidate/core";
import {required, requiredIf} from "@vuelidate/validators";
import {watchOnce} from "@vueuse/core";
import {debounce} from "lodash";
import {computed, onBeforeMount, reactive, ref, watch} from "vue";

import DateTimeInput from "@/js/components/bases/DateTimeInput.vue";
import TextInput from "@/js/components/bases/TextInput.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {formService} from "@/js/service/formService";

type EventType = {
    id: number,
    name: string,
    value: string
}
type EventTypeLocationTimeType = {
    eventType: number
    eventLocation: {
        url?: string,
        address?: string,
    },
    startTime: Date,
    endTime: Date
}

const props = defineProps({
    typeLocationTime: {
        type: Object as () => EventTypeLocationTimeType,
        required: false,
        default: () => {
        }
    },
    label: {
        type: String,
        required: false,
        default: "Select type"
    },
})

const emits = defineEmits<{
    (e: 'emitEventTypeLocationTime', data: EventTypeLocationTimeType): void
}>()

const selectedEventType = ref(0)
const availableTypes = ref<EventType[]>([])


onBeforeMount(() => {
    formService.getTypes(API_ENDPOINTS.EVENT.FETCH_EVENT_TYPES).then(res => {
        availableTypes.value = res.data
    }).catch(err => {
        console.log(err)
    })
})

// Computed variable to keep track what field (between url and/or address) to be rendered based on selectedEventType //
const showAddressOrUrlFields = computed((): ref<{ url: boolean, address: boolean }> => {
    const result = {
        url: false,
        address: false
    }
    const selectedType: EventType = availableTypes.value.find(item => item.id === selectedEventType.value)
    if (selectedType) {
        switch (selectedType.name) {
        case 'Virtual':
            result.url = true
            break;
        case 'In Person':
            result.address = true
            break;
        case 'Hybrid':
            result.url = true
            result.address = true
            break
        }
    }
    return result
})

const state = reactive({
    startTime: null,
    endTime: null,
    url: '',
    address: ''
})

const rules = {
    startTime: {required},
    endTime: {required},
    url: {requiredIf: requiredIf(showAddressOrUrlFields.value.url)},
    address: {requiredIf: requiredIf(showAddressOrUrlFields.value.address)}
}
const v$ = useVuelidate(rules, state)

const emitEventTypeLocationTime = (): void => {
    const data: EventTypeLocationTimeType = {
        eventType: selectedEventType.value,
        eventLocation: {
            url: state.url || '',
            address: state.address || '',
        },
        startTime: state.startTime,
        endTime: state.endTime
    }
    emits('emitEventTypeLocationTime', data)
}

const debouncedEmitEventTypeLocationTime = debounce(emitEventTypeLocationTime, 500)

watch(state, () => {
    debouncedEmitEventTypeLocationTime()
})

const populateLocalStateFromBaseProps = () =>{
    selectedEventType.value = props.typeLocationTime.eventType
    state.startTime = props.typeLocationTime.startTime
    state.endTime = props.typeLocationTime.endTime
    state.url = props.typeLocationTime.eventLocation.url || ''
    state.address = props.typeLocationTime.eventLocation.address || ''
}
watchOnce(props.typeLocationTime, () =>{
    populateLocalStateFromBaseProps()
})
</script>

<template>
    <div class="TypeContainer flex flex-col mb-4">
        <div class="TypeLabel">
            {{ props.label }}
        </div>
        <div class="TypeDropdown flex justify-around flex-row mb-4">
            <select
                v-model="selectedEventType"
                class="border-[1px] border-gray-300 p-2 rounded"
            >
                <option
                    disabled
                    selected
                    :value="0"
                >
                    Event type
                </option>
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
        <!--  Start text box for time and addresses      -->
        <div class="addressAndUrlRow flex flex-row gap-4">
            <TextInput
                v-if="showAddressOrUrlFields.address"
                v-model="v$.address.$model"
                :v$="v$.address"
                field-id="address "
                class="grow my-2"
                placeholder=""
            >
                <template #label>
                    Address
                </template>
            </TextInput>
            <TextInput
                v-if="showAddressOrUrlFields.url"
                v-model="v$.url.$model"
                :v$="v$.url"
                field-id="url"
                class="grow my-2"
                placeholder=""
            >
                <template #label>
                    URL link
                </template>
            </TextInput>
        </div>
        <div class="flex flex-row gap-4 timeRow">
            <DateTimeInput
                v-model="v$.startTime.$model"
                class="grow"
                field-id="eventStartTime"
                :v$="v$.startTime"
            >
                <template #label>
                    Start time
                </template>
            </DateTimeInput>
            <DateTimeInput
                v-model="v$.endTime.$model"
                class="grow"
                field-id="eventEndTime"
                :v$="v$.endTime"
            >
                <template #label>
                    End time
                </template>
            </DateTimeInput>
        </div>
    </div>
</template>
