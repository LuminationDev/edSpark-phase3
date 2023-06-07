<script setup>
import {ref, computed} from 'vue'
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
    }
})
const selectedValue = ref([])

const emits = defineEmits(['transmitSelectedFilters'])

const handleEmitSelectedFilters = () =>{
    emits('transmitSelectedFilters', selectedValue.value, props.dataPath)
}

</script>

<template>
    <div class="multiselectContainer w-full mx-10 mt-4 p-2 text-lg">
        <div
            class="multiselectOutsideTitle pb-2 transition-opacity"
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
            label="name"
            track-by="name"
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
