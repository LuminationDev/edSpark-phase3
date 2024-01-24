<script setup>
import {computed,ref} from 'vue'

const perPage = defineModel()
</script>

<template>
    <div class="flex items-center flex-col perPageContainer">
        <div class="mb-2 mt-4 text-base title">
            Number of items per page
        </div>
        <select
            v-model="perPage"
            name="perPage"
            class="border-[1px] border-slate-300 px-4 py-2 rounded w-24"
        >
            <option :value="12">
                12
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
