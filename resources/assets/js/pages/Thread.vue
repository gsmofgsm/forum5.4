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
            toggleLock () {
                // ajax
                axios[ this.locked ? 'delete' : 'post' ]('/locked-threads/' + this.thread.slug);

                this.locked = ! this.locked;
            }
        },

        components: { Replies, SubscribeButton }
    }
</script>