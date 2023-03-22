<script>
    export default {

    }
</script>

<template>
    <div class="col-span-1 row-span-1 flex flex-row gap-4 relative place-items-center">
        <slot name="fieldName"></slot>

        <slot name="updateField"></slot>

        <slot name="currentDetails"></slot>

        <slot name="saveChanges"></slot>

        <slot name="cancelUpdate"></slot>

    </div>
</template>
