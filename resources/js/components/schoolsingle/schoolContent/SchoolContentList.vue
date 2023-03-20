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
        <ol class="list-decimal ml-2">
            <li
                v-for="(listItem,index) in data.data.items"
                :key="index"
                class="pb-1 ml-1"
            >
                <span v-html="listItem" />
            </li>
        </ol>
    </div>
    <div v-else>
        <ul>
            <li
                v-for="(listItem,index) in data.data.items"
                :key="index"
                class="pb-1"
            >
                <span v-html="listItem" />
            </li>
        </ul>
    </div>
</template>