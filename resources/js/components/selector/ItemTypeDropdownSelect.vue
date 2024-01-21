<script setup>
import {onBeforeMount, ref, watch} from 'vue'

import ErrorMessages from "@/js/components/bases/ErrorMessages.vue";
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
    },
    v$: {
        type: Object,
        required: false,
        default: {}
    }
})

const availableTypes = ref([])
const selectedTypes = ref('')


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
        emits('sendSelectedTypesAsArray', Array.isArray(selectedTypes.value) ? selectedTypes.value : [selectedTypes.value])
    }
})

</script>

<template>
    <div class="TypeContainer flex flex-col mb-4">
        <div class="TypeLabel ml-2">
            {{ props.label }}
        </div>
        <div class="TypeCheckboxList flex flex-row w-full">
            <div
                class="TypeCheckboxContainer flex items-center flex-row pb-4 w-full  first:pl-0 gap-4"
            >
                <select
                    v-model="selectedTypes"
                    type="checkbox"
                    class="border-slate-300 px-2 py-2 rounded w-full"
                    :class="{'text-slate-500 font-light':!selectedTypes }"
                    name="type"
                >
                    <option
                        disabled
                        selected
                        value=""
                    >
                        Select type
                    </option>
                    <option
                        v-for="(type,index) in availableTypes"
                        :key="index"
                        :value="type.id"
                    >
                        {{ type.name }}
                    </option>
                </select>
            </div>
        </div>
        <ErrorMessages :v$="props.v$" />
    </div>
</template>
