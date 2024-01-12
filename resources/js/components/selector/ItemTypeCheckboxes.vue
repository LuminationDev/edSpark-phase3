<script setup>
import {onBeforeMount, ref, watch} from 'vue'

import {formService} from "@/js/service/formService";


const props = defineProps({
    label: {
        type: String,
        required: false,
        default: "Select type"
    },
    typeApiLink: {
        type: String,
        required: true
    },
    selectedType: {
        type: Array,
        required: false,
        default: []
    }
})

const availableTypes = ref([])
const selectedTypes = ref([])


onBeforeMount(() => {
    formService.getTypes(props.typeApiLink).then(res => {
        availableTypes.value = res.data
        // iterate fetched types and check against the pre-filled items
        availableTypes.value.forEach(item => {
            props.selectedType.forEach(selectedItem => {
                if (Object.values(item).includes(selectedItem)) {
                    selectedTypes.value.push(item['id'])
                }
            })
        })
    }).catch(err => {
        console.log(err)
    })
})

const emits = defineEmits(['sendSelectedTypesAsArray'])

watch(selectedTypes, () => {
    if (selectedTypes.value) {
        if (!Array.isArray(selectedTypes.value)) {
            selectedTypes.value = [selectedTypes.value]
        }
        emits('sendSelectedTypesAsArray', selectedTypes.value)
    }
})

</script>

<template>
    <div class="TypeContainer flex flex-col mb-4">
        <div class="TypeLabel">
            {{ props.label }}
        </div>
        <div class="TypeCheckboxList flex flex-row w-full">
            <div
                v-for="(type,index) in availableTypes"
                :key="index + type.id"
                class="TypeCheckboxContainer flex items-center flex-row gap-4 px-6 py-4 first:pl-0"
            >
                <input
                    v-model="selectedTypes"
                    type="checkbox"
                    class="rounded text-main-teal"
                    :name="type.name"
                    :value="type.id"
                >
                <label for="LabelTypeSelector">{{ type.name }}</label>
            </div>
        </div>
    </div>
</template>
