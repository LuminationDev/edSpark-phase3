<script setup>
import {ref} from 'vue'
const searchTerm = ref('')

const props = defineProps({
    placeholder:{
        type: String,
        required: false,
        default: 'Enter search term'
    }
})
const handleSearchChange = () =>{
    emits('emitSearchTerm',searchTerm.value)
}

const emits = defineEmits(['emitSearchTerm'])
</script>
<template>
    <div
        style="position: relative"
        class="!border-black border-2 flex items-center font-medium gap-1 h-12 pl-4 rounded text-black text-lg"
    >
        <p id="searchTitle">
            Search
        </p>
        <input
            id="searchbar-text-input"
            v-model="searchTerm"
            class="bg-transparent border-0 font-light ml-2 text-lg"
            type="text"
            :placeholder="props.placeholder"
            @input="handleSearchChange"
        >
        <div
            id="searchIcon"
            style="width: 28px; position: absolute; right: 5px; z-index: -1;"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
            >
                <circle
                    cx="10"
                    cy="10"
                    r="8" 
                    stroke="#000" 
                    stroke-width="2" 
                    stroke-linecap="round" 
                    stroke-linejoin="round" 
                />

                <line
                    x1="15"
                    y1="16"
                    x2="21"
                    y2="22"                
                    stroke="#000" 
                    stroke-width="2" 
                    stroke-linecap="round" 
                    stroke-linejoin="round" 
                />

            </svg>
        </div>
    </div>
</template>

