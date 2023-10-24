<script setup>
import {computed, ref} from 'vue'

import fontLibrary from './data/iconPickerData'

const props = defineProps({
    label: {
        type: String,
        required: false,
        default: 'Vue3 Icon Picker'
    },
    modelValue: {
        type: String,
        required: false,
        default: 'fas fa-circle'
    }
})

const filterText = ref('')
const activeGlyph = ref(props.modelValue)
const isVisible = ref(true)

const tabs = [
    {
        id: 'fas',
        title: 'Font Awesome Solid',
        icon: 'fab fa-font-awesome',
        link: fontLibrary.fontAwesome.variants.solid
    },

]

const activeTab = ref(tabs[0])

const allGlyphs = [].concat(
    tabs[0].link.icons
)

const glyphs = computed(() => {
    let _glyphs = []
    if (activeTab.value.id !== 'all') {
        _glyphs = activeTab.value.link.icons
    } else {
        _glyphs = allGlyphs
    }

    if (filterText.value !== '') {
        const _filterText = filterText.value.toLowerCase()
        _glyphs = _glyphs.filter(
            item => item.substr(7, filterText.value.length) === _filterText
        )
    }
    return _glyphs
})

const setActiveGlyph = glyph => {
    activeGlyph.value = glyph
}

const isActiveGlyph = glyph => activeGlyph.value === glyph
const isActiveTab = tab => tab === activeTab.value.id

const setActiveTab = tab => {
    activeTab.value = tab
    // filterText.value=''; //nice feature
}

const getGlyphName = glyph => glyph.replace(/f.. fa-/g, '').replace('-', ' ')

const emit = defineEmits(['update:modelValue'])

const insert = () => {
    emit('update:modelValue', activeGlyph.value)
    isVisible.value = false
}

const togglePicker = () => {
    isVisible.value = !isVisible.value
}

const closePicker = () => {
    isVisible.value = false
}
</script>
<template>
    <span
        v-bind="$attrs"
        @click="togglePicker"
    >
        <i
            class="vue3-icon-picker"
            :class="modelValue"
        />
    </span>

    <div
        v-if="isVisible"
        class="aim-modal aim-open"
    >
        <div class="aim-modal--content">
            <div class="aim-modal--header">
                <div class="aim-modal--header-logo-area">
                    <span class="aim-modal--header-logo-title">
                        {{ label }}
                    </span>
                </div>
                <div
                    class="aim-modal--header-close-btn"
                    @click="closePicker"
                >
                    <i
                        class="fa-times fas"
                        title="Close"
                    />
                </div>
            </div>
            <div class="aim-modal--body">
                <div class="aim-modal--sidebar">
                    <div class="aim-modal--sidebar-tabs">
                        <div
                            v-for="tab in tabs"
                            :key="tab.id"
                            class="aim-modal--sidebar-tab-item"
                            data-library-id="all"
                            :class="{ 'aesthetic-active': isActiveTab(tab.id) }"
                            @click="setActiveTab(tab)"
                        >
                            <i :class="tab.icon" />
                            <span>{{ tab.title }}</span>
                        </div>
                    </div>
                    <div class="aim-sidebar-preview">
                        <div class="aim-icon-item">
                            <div class="aim-icon-item-inner">
                                <i :class="activeGlyph" />
                                <div class="aim-icon-item-name">
                                    {{ activeGlyph }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="aim-modal--icon-preview-wrap">
                    <div class="aim-modal--icon-search">
                        <input
                            v-model="filterText"
                            placeholder="Filter by name..."
                        >
                        <i class="fa-search fas" />
                    </div>
                    <div class="aim-modal--icon-preview-inner">
                        <div class="aim-modal--icon-preview">
                            <div
                                v-for="glyph in glyphs"
                                :key="glyph"
                                class="aim-icon-item"
                                :class="{ 'aesthetic-selected': isActiveGlyph(glyph) }"
                                @click="setActiveGlyph(glyph)"
                            >
                                <div class="aim-icon-item-inner">
                                    <i :class="glyph" />
                                    <div class="aim-icon-item-name">
                                        {{ getGlyphName(glyph) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="aim-modal--footer">
                <button
                    class="aim-insert-icon-button"
                    @click="insert"
                >
                    Insert
                </button>
            </div>
        </div>
    </div>
</template>
<style scoped>
@import './css/style.css';
</style>
