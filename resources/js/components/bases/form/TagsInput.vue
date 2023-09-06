<script setup>
import {ref} from "vue";
import Vue3TagsInput from 'vue3-tags-input';

const props = defineProps({
    v$: {
        type: Object,
        required: true
    },
    modelValue: {
        type: String,
        required: true
    },
    fieldId: {
        type: String,
        required: true
    },
    placeholder: {
        type: String,
        required: false,
        default: 'Type and seperate each tag with comma or press enter'
    }
})

const tags = ref(props.modelValue)
const emit = defineEmits(['update:modelValue','inputUpdate'])

/* can fetch available tags for this model to make it selectable
    https://vue3-tags-input.netlify.app/examples/select
 */

const handleChangeTag = (newtags) => {
    tags.value = newtags
    emit('update:modelValue', newtags)

}
</script>

<template>
    <div class="flex-col mb-4">
        <label
            class="h-8 ml-2"
            :for="fieldId"
        >
            <slot name="label" />
        </label>
        <Vue3TagsInput
            :id="fieldId"
            :tags="tags"
            placeholder="Add or create tags"
            class="!border-gray-300 border-2 py-1 rounded text-black"
            @on-tags-changed="handleChangeTag"
        />
    </div>
</template>

<style scoped>
.v3ti :deep(.v3ti-tag) {
    background: #097982 ;
}

.v3ti :deep(.v3ti-tag .v3ti-remove-tag) {
    color: #be123c;
    transition: color .3s;
}

.v3ti .v3ti-tag .v3ti-remove-tag:hover {
    color: #ffffff;
}

</style>