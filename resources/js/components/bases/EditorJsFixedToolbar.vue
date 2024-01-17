<script setup>

const {
    editorInstance,
    undoRedo,
    textFormatting,
    alignment
} = defineProps(['editorInstance', 'undoRedo', 'textFormatting', 'alignment']);


const emits = defineEmits(['undo', 'redo', 'toggleFormat', 'alignmentChange'])
const undo = () => {
    emits('undo');
};

const redo = () => {
    emits('redo');
};

const toggleFormat = (format) => {
    emits('toggleFormat', format);
};

const handleAlignmentChange = (event) => {
    emits('alignmentChange', event.target.value);
};
</script>

<template>
    <div id="fixedToolbar">
        <button
            :disabled="!undoRedo.undoAvailable"
            @click="undo"
        >
            Undo
        </button>
        <button
            :disabled="!undoRedo.redoAvailable"
            @click="redo"
        >
            Redo
        </button>
        <button
            :class="{ active: textFormatting.bold }"
            @click="toggleFormat('bold')"
        >
            Bold
        </button>
        <button
            :class="{ active: textFormatting.italic }"
            @click="toggleFormat('italic')"
        >
            Italic
        </button>
        <button
            :class="{ active: textFormatting.underline }"
            @click="toggleFormat('underline')"
        >
            Underline
        </button>

        <!-- Alignment options -->
        <label>
            <input
                v-model="alignment"
                type="radio"
                value="left"
                @change="handleAlignmentChange"
            > Left
        </label>
        <label>
            <input
                v-model="alignment"
                type="radio"
                value="center"
                @change="handleAlignmentChange"
            > Center
        </label>
        <label>
            <input
                v-model="alignment"
                type="radio"
                value="right"
                @change="handleAlignmentChange"
            > Right
        </label>

        <!-- Add more buttons for other formatting options -->
    </div>
</template>

<style scoped>
#fixedToolbar {
    position: fixed;
    top: 0;
    left: 0;
    background-color: #fff;
    border-bottom: 1px solid #ccc;
    padding: 5px;
    z-index: 1000;
}

#fixedToolbar button {
    margin-right: 5px;
}

#fixedToolbar button.active {
    font-weight: bold; /* Adjust styling for active state as needed */
}
</style>
