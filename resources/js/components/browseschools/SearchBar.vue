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
    <div class="searchbar w-[80%] flex justify-center items-center ">
        <input
            id="searchbar-text-input"
            v-model="searchTerm"
            class="!w-[50%] h-12 border-2 !border-black  text-black text-lg rounded mt-4 px-4"
            type="text"
            :placeholder="props.placeholder"
            @input="handleSearchChange"
        >
    </div>
</template>

