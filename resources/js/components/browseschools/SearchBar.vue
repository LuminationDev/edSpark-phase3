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
    <div class="flex justify-center items-center searchbar w-full lg:!w-4/5">
        <input
            id="searchbar-text-input"
            v-model="searchTerm"
            class="!border-black !w-[50%] border-2 h-12 mt-4 px-4 rounded text-black text-lg"
            type="text"
            :placeholder="props.placeholder"
            @input="handleSearchChange"
        >
    </div>
</template>

