<script setup>
import {ref} from 'vue'
import Multiselect from "vue-multiselect";

const props = defineProps({
    filterList: {
        type: Array,
        required: true
    },
    dataPath: {
        type: String, required: true
    },
    placeholder: {
        type: String,
        required: true
    },
    id: {
        type: String,
        required: true
    },
    preselected: {
        type: Object,
        required: false,
        default: () => {}
    }
})
const selectedValue = ref([])

const emits = defineEmits(['transmitSelectedFilters'])

const handleEmitSelectedFilters = () => {
    emits('transmitSelectedFilters', selectedValue.value, props.dataPath)
}
if (props.preselected) {
    selectedValue.value.push(props.preselected)
    handleEmitSelectedFilters()
}

</script>


<template>
    <div
        class="!border-black border-2 flex items-center font-medium gap-1 h-12 h-auto mt-4 pl-4 rounded text-black text-lg"
    >
        <div class="filter-input pr-2">
            {{ props.placeholder }}
        </div>
        <Multiselect
            :id="props.id"
            v-model="selectedValue"
            :options="props.filterList"
            :multiple="true"
            :close-on-select="false"
            label="name"
            track-by="name"
            aria-expanded="false"
            aria-controls="resourceResult"
            deselect-label="X"
            select-label="[Enter]"
            selected-label=""
            @select="handleEmitSelectedFilters"
            @remove="handleEmitSelectedFilters"
        />
    </div>
</template>

<style>

.filter-input {
    white-space: nowrap;
}


@media screen and (max-width: 430px) {
    .filter-input {
        white-space: wrap;
    }
}

@media screen and (max-width: 790px) {

    .filter-input {
        white-space: normal;
    }
}

@media screen and (max-width: 510px) {
    .search-filter-components > div {
        flex-direction: column;
        height: auto !important;
        padding-left: 0 !important;
    }

    .filter-input {
        padding-top: 0.5rem;
        padding-right: 0 !important;
        white-space: normal;
    }

    #searchTitle {
        margin-left: 25px; /* centre with icon */
        padding-top: 0.5rem;
    }

    #searchIcon {
        margin-right: 7rem !important;
        margin-top: 0.5rem !important;
        right: unset !important;
        margin-left: 35px; /* centre with icon */
    }

    /* not enough screen real estate to display these */
    .multiselect__option--highlight.multiselect__option::after,
    .multiselect__option--selected.multiselect__option--highlight::after {
        display: none !important;
    }
}


.multiselectContainer {
    color: black !important;
    border-color: black !important;
}

.multiselect__placeholder {
    font-size: 18px;
    padding-left: 4px;
    color: black !important;
}

.multiselect__tag {
    font-size: 16px !important;
    padding: 8px 20px 8px 8px !important;
    background: #002858 !important;
    margin-bottom: 0 !important;
}

.multiselect__tag span {
    cursor: pointer;
}

.multiselect__tags {
    border-width: 0px !important;
    border-color: black !important;
    font-weight: 300 !important;
    background-color: unset !important;

}

.multiselect__input {
    font-size: 18px !important;
    color: black !important;

}

.multiselect__option {
    font-size: 16px !important;
    font-weight: 500 !important;
}

.multiselect__option--highlight {
    /*background: #002858 !important;*/
    /*color: white !important;*/
    background: white !important;
    color: black !important;
}

.multiselect__option--highlight:hover {
    background: #002858 !important;
    color: white !important;
}


.multiselect__option--highlight.multiselect__option::after {
    /*background: #002858;*/
    background: white !important;
    color: black !important;
}

.multiselect__option--highlight.multiselect__option:hover::after {
    background: #002858 !important;
    color: white !important;
}

.multiselect__element > span::after {
    padding: 0 8px !important;
}

.multiselect__option--selected.multiselect__option--highlight[data-deselect="X"] {
    background: #f3f3f3;
    color: unset;
}
.multiselect__option--selected.multiselect__option--highlight[data-deselect="X"]::after {
    background: #ff6a6a !important;
    color: unset;
}
.multiselect__tag-icon::after,
.multiselect__tag-icon {
    color: #FFF !important;
    line-height: 32px !important;
    padding-left: 2px;
}

.multiselect__select {
    top: 6px;
    line-height: unset !important;
}

</style>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
