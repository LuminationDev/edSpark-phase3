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
    <div class="searchbar w-[80%] flex justify-center items-center">
        <input
            id="searchbar-text-input"
            v-model="searchTerm"
            class="!w-[60%] h-12 border-4 !border-slate-300 shadow-md rounded mt-4 px-12"
            type="text"
            :placeholder="props.placeholder"
            @input="handleSearchChange"
        >
    </div>
</template>

