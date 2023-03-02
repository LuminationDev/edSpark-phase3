<script>
    export default {
        props: {
            posts: Array
        },

        methods: {
            checkData() {
                console.log(this.posts);
            }
        },

        mounted() {
            this.checkData();
        },
    }
</script>

<template>
    <div class="grid grid-cols-3 gap-[24px] w-full">
        <div class="col-span-1 shadow-lg px-[24px] py-[18px]" v-for="post in this.posts">
            <h5 class=" text-[24px] font-medium">
                {{ post.title }}
            </h5>
            <p><small>{{ post.created_at }}</small></p>
            <p>{{ post.description }}</p>
        </div>

    </div>
</template>
