<script>
    export default {
        props: {
            // posts: Array
        },

        methods: {
            checkData() {
                // console.log(this.posts);
            }
        },

        mounted() {
            this.checkData();
        },
    }
</script>

<template>
    <slot name="cover"></slot>
    <div class="px-[24px] py-[18px] h-72 group-hover:h-full transition-all overflow-ellipsis overflow-hidden">

        <h5 class=" text-[24px] font-medium">
            <slot name="title"></slot>
        </h5>
        <p><small><slot name="created_at"></slot></small></p>
        <p class=""><slot name="description"></slot></p>
    </div>

</template>
