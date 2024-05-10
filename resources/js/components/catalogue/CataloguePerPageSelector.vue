<script setup>

const perPage = defineModel()
</script>

<template>
    <div class="flex flex-col perPageContainer">
        <div class="mb-2 mt-4 text-lg title">
            Items per page
        </div>
        <select
            v-model="perPage"
            name="perPage"
            class="border-[1px] border-slate-300 px-4 py-2 rounded-lg shadow w-full"
        >
            <option :value="16">
                16
            </option>
            <option :value="24">
                24
            </option>
            <option :value="36">
                36
            </option>
            <option :value="48">
                48
            </option>
        </select>
    </div>
</template>
