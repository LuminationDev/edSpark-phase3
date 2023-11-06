<script setup>
import {onBeforeMount,ref, watch} from 'vue'

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
        console.log(availableTypes.value.filter(item => props.selectedType.includes(item)))
        console.log('inside then avail types')
        selectedTypes.value = availableTypes.value.filter(item => props.selectedType.includes(item))
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
        <div class="TypeCheckboxList flex justify-around flex-row">
            <div
                v-for="(type,index) in availableTypes"
                :key="index + type.id"
                class="TypeCheckboxContainer flex items-center flex-row gap-4 p-4"
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
