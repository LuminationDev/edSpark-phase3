<script setup>
import {ref, computed, onMounted} from 'vue'
import Multiselect from "vue-multiselect";
const props = defineProps({
    filterList:{
        type: Array,
        required: true
    },
    dataPath:{
        type: String, required: true
    },
    placeholder:{
        type: String,
        required: true
    },
    id:{
        type: String,
        required: true
    },
    preselected:{
        type: Object,
        required: false,
        default: () => {}
    }
})
const selectedValue = ref([])

const emits = defineEmits(['transmitSelectedFilters'])

const handleEmitSelectedFilters = () =>{
    emits('transmitSelectedFilters', selectedValue.value, props.dataPath)
}
if(props.preselected){
    selectedValue.value.push(props.preselected)
    handleEmitSelectedFilters()
}

</script>

<template>
    <div class="mt-4 multiselectContainer mx-2 p-2 text-lg w-4/5 lg:!mx-10 lg:!w-full">
        <div
            class="multiselectOutsideTitle pb-2 text-gray-200 transition-opacity"
            :class="{'!opacity-0 ' : selectedValue.length < 1}"
        >
            {{ placeholder }}
        </div>
        <Multiselect
            v-model="selectedValue"
            :options="props.filterList"
            :multiple="true"
            :placeholder="props.placeholder"
            :close-on-select="false"
            :id="props.id"
            label="name"
            track-by="name"
            aria-expanded="false"
            aria-controls="resourceResult"
            @select="handleEmitSelectedFilters"
            @remove="handleEmitSelectedFilters"
        />
    </div>
</template>
<style>
.multiselectContainer{
    color:black !important;
    border-color: black !important;
}

.multiselect__placeholder{
    font-size: 18px;
    padding-left: 4px;
    color: black !important;
}

.multiselect__tag{
    font-size: 16px !important;
    padding: 8px 20px 8px 8px !important;
    background: #339999 !important;
}
.multiselect__tag span{
    cursor: pointer;
}
.multiselect__tags{
    border-width: 2px !important;
    border-color: black !important

 }
.multiselect__input{
    font-size: 18px !important;
    color:black !important;

}
.multiselect__option{
    font-size: 18px !important;
}

.multiselect__option--highlight{
    background: #339999 !important;

}
.multiselect__option--highlight.multiselect__option::after{
    background: #339999;
}


</style>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
