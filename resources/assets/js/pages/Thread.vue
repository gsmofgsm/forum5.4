<template>

</template>

<script>
    import Replies from '../components/Replies.vue';
    import SubscribeButton from '../components/SubscribeButton.vue';

    export default {
        props: ['thread'],

        data() {
            return {
                repliesCount: this.thread.replies_count,
                locked: this.thread.locked
            }
        },

        methods: {
            lock () {
                this.locked = true;

                // ajax
                axios.post('/locked-threads/' + this.thread.slug);
            }
        },

        components: { Replies, SubscribeButton }
    }
</script>