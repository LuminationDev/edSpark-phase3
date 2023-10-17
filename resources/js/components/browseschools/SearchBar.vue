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
    <div style="position: relative" class="flex items-center font-medium gap-1 !border-black border-2 h-12 pl-4 rounded text-black text-lg">
        <P>Search</P>
        <input
            id="searchbar-text-input"
            v-model="searchTerm"
            class="border-0 text-lg font-light bg-transparent"
            type="text"
            :placeholder="props.placeholder"
            @input="handleSearchChange"
        >
        <div style="width: 28px; position: absolute; right: 5px; z-index: -1;">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
            >
                <path d="M11 19.5C15.4183 19.5 19 15.9183 19 11.5C19 7.08172 15.4183 3.5 11 3.5C6.58172 3.5 3 7.08172 3 11.5C3 15.9183 6.58172 19.5 11 19.5Z" 
                    stroke="#000" 
                    stroke-width="2" 
                    stroke-linecap="round" 
                    stroke-linejoin="round"/>

                <path d="M20.9999 21.4999L16.6499 17.1499" 
                    stroke="#000" 
                    stroke-width="2" 
                    stroke-linecap="round" 
                    stroke-linejoin="round"/>

            </svg>
        </div>
    </div>
</template>

