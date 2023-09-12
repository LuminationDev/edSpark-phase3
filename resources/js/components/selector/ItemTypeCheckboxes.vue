<script setup>
import {ref, computed, watch} from 'vue'

const props = defineProps({
    label: {
        type: String,
        required: false,
        default: "Select type"
    },
    data: {
        type: Object,
        required: true
    }
})

const selectedTypes = ref([])

const emits = defineEmits(['sendSelectedTypesAsArray'])

watch(selectedTypes, () =>{
    if(selectedTypes.value){
        if(!Array.isArray(selectedTypes.value)){
            selectedTypes.value = [selectedTypes.value]
        }
        console.log(selectedTypes.value)
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
                v-for="(type,index) in props.data"
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
