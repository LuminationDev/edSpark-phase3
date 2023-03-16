<script setup>
const props = defineProps({
    data:{
        type: Object,
        required: true
    }
})

</script>
<template>
    <div v-if="data.data.style == 'ordered'">
        <ol>
            <li
                v-for="(listItem,index) in data.data.items"
                :key="index"
            >
                {{ listItem }}
            </li>
        </ol>
    </div>
    <div v-else>
        <ul>
            <li
                v-for="(listItem,index) in data.data.items"
                :key="index"
            >
                {{ listItem }}
            </li>
        </ul>
    </div>
</template>