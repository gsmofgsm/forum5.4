<template>

</template>

<script>
    import Replies from '../components/Replies.vue';

    export default {
        props: ['initialRepliesCount'],

        data() {
            return {
                repliesCount: this.initialRepliesCount
            }
        },

        components: { Replies }
    }
</script>