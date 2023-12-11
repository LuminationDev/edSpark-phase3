<script setup>
const props = defineProps({
    currentUserEMSLink: {
        type: String,
        required: true,
    },
})
console.log(props.currentUserEMSLink)
</script>


<template>
    <div
        class="border-b-2 border-white flex flex-col gap-2 py-8 rsvpFormInputs"
    >
        {{ props.currentUserEMSLink || "Sorry, link not available" }}
    </div>
</template>
