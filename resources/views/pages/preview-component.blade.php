<div id="preview-component">
    <preview-component :title="{{ json_encode($title) }}" :description="{{ json_encode($description) }}"></preview-component>
</div>
<script type="module">
    import { createApp } from "vue";
    import PreviewComponent from "../../js/pages/PreviewComponent.vue";
    // export default {
    //     components: {PreviewComponent}
    // }

    createApp({
        components: {
            PreviewComponent,
        },
    }).mount('#preview-component');
</script>
