<script setup lang="ts">

</script>

<template>
    <div
        class="bg-gray-50 border-2 overflow-y-auto rounded-2xl w-[40%] fixed right-32 z-[99]"
    >
        <div class="m-6">
            <p class="h3">Windows</p>
            <p class="font-bold">
                How do I take a screenshot?
            </p>
            <p>
                Hold down [Win] and [Shift] then press [S].
                Use your mouse to draw a rectangle around what you want to capture.
                Windows then copies it to the clipboard.
            </p><br>

            <p class="font-bold">
                How do I attach that to this form?
            </p>
            <p>
                Click inside the rich text editor window.
                Hold down [Ctrl] then press [V].
            </p>
            <br><br>
            <p class="h3">Mac</p>
            <p class="font-bold">
                How do I take a screenshot?
            </p>

            <p>
                Hold down [Command] and [Shift] then press [3].
                The screenshot should be automatically saved to your desktop.
            </p><br>

            <p class="font-bold">
                How do I attach that to this form?
            </p>
            <p>
                Click the ‘insert image’ icon on the editor menu.
                Use the file explorer to find your file, then select it.
            </p>
        </div>
    </div>
</template>

<style scoped>
 .h3 {
    font-size: large;
    font-weight: 700;
    padding-bottom: 8px;
 }
</style>